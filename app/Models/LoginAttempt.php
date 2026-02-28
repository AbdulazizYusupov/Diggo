<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'ip_address',
        'user_agent',
        'successful',
        'attempted_at',
        // Yangi ustunlar
        'user_id',          // Muvaffaqiyatli bo'lsa qaysi user
        'blocked',          // Bu urinish bloklangan paytda bo'ldimi
        'failure_reason',   // 'wrong_password' | 'user_not_found' | 'user_inactive' | 'rate_limited'
        'browser',
        'platform',
        'device',
        'country',          // IP geo (ixtiyoriy, keyinchalik qo'shilishi mumkin)
        'risk_score',       // 0-100: qanchalik shubhali
    ];

    protected $casts = [
        'successful'   => 'boolean',
        'blocked'      => 'boolean',
        'attempted_at' => 'datetime',
        'risk_score'   => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeFailed($query)
    {
        return $query->where('successful', false);
    }

    public function scopeSuccessful($query)
    {
        return $query->where('successful', true);
    }

    public function scopeBlocked($query)
    {
        return $query->where('blocked', true);
    }

    public function scopeRecent($query, int $minutes = 5)
    {
        return $query->where('attempted_at', '>=', now()->subMinutes($minutes));
    }

    public function scopeForPhone($query, string $phone)
    {
        return $query->where('phone', $phone);
    }

    public function scopeForIp($query, string $ip)
    {
        return $query->where('ip_address', $ip);
    }

    public function scopeHighRisk($query, int $threshold = 70)
    {
        return $query->where('risk_score', '>=', $threshold);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('attempted_at', today());
    }

    // ─── Static helpers ───────────────────────────────────────────────────────

    /**
     * So'nggi X daqiqada berilgan phone + IP uchun xato urinishlar soni
     */
    public static function getRecentFailedAttempts(string $phone, string $ip, int $minutes = 15): int
    {
        return static::failed()
            ->where(function ($q) use ($phone, $ip) {
                $q->forPhone($phone)->orWhere('ip_address', $ip);
            })
            ->recent($minutes)
            ->count();
    }

    /**
     * Berilgan IP oxirgi 1 soatda nechta xato qilgan
     */
    public static function getFailedAttemptsForIp(string $ip, int $minutes = 60): int
    {
        return static::failed()->forIp($ip)->recent($minutes)->count();
    }

    /**
     * Berilgan phone oxirgi 1 soatda nechta xato qilgan
     */
    public static function getFailedAttemptsForPhone(string $phone, int $minutes = 60): int
    {
        return static::failed()->forPhone($phone)->recent($minutes)->count();
    }

    /**
     * Risk score hisoblash (0–100)
     * Qanchalik ko'p xato → qanchalik yuqori risk
     */
    // public static function calculateRiskScore(string $phone, string $ip): int
    // {
    //     $score = 0;

    //     // So'nggi 5 daqiqada xatolar
    //     $recent5  = static::failed()->where(fn($q) => $q->forPhone($phone)->orWhere('ip_address', $ip))->recent(5)->count();
    //     // So'nggi 1 soatda xatolar
    //     $recent60 = static::failed()->where(fn($q) => $q->forPhone($phone)->orWhere('ip_address', $ip))->recent(60)->count();
    //     // Bir xil IP dan turli phonelarga urinishlar (credential stuffing belgisi)
    //     $ipVariety = static::failed()->forIp($ip)->recent(60)->distinct('phone')->count('phone');

    //     $score += min($recent5  * 15, 45);  // Max 45 ball (5 daqiqa ichida)
    //     $score += min($recent60 * 5,  30);  // Max 30 ball (1 soat ichida)
    //     $score += min($ipVariety * 10, 25); // Max 25 ball (IP diversity)

    //     return min($score, 100);
    // }
    public static function calculateRiskScore(string $phone, string $ip): int
    {
        $score = 0;

        // 3 ta query o'rniga 1 ta query — PHP da hisoblash
        $attempts = static::failed()
            ->where(fn($q) => $q->forPhone($phone)->orWhere('ip_address', $ip))
            ->recent(60)
            ->select(['attempted_at', 'ip_address', 'phone'])
            ->get();
        $recent5   = $attempts->where('attempted_at', '>=', now()->subMinutes(5))->count();
        $recent60  = $attempts->count();
        $ipVariety = $attempts->where('ip_address', $ip)->pluck('phone')->unique()->count();
        $score += min($recent5   * 15, 45);  // Max 45 ball (5 daqiqa ichida)
        $score += min($recent60  * 5,  30);  // Max 30 ball (1 soat ichida)
        $score += min($ipVariety * 10, 25);  // Max 25 ball (IP diversity)
        return min($score, 100);
    }

    /**
     * Eski yozuvlarni o'chirish
     */
    public static function cleanOldAttempts(int $days = 30): int
    {
        return static::where('attempted_at', '<', now()->subDays($days))->delete();
    }

    /**
     * Berilgan IP bloklanishi kerakmi? (1 soatda 20+ xato)
     */
    public static function shouldBlockIp(string $ip, int $maxAttempts = 20, int $minutes = 60): bool
    {
        return static::getFailedAttemptsForIp($ip, $minutes) >= $maxAttempts;
    }

    /**
     * Berilgan phone bloklanishi kerakmi? (1 soatda 10+ xato)
     */
    public static function shouldBlockPhone(string $phone, int $maxAttempts = 10, int $minutes = 60): bool
    {
        return static::getFailedAttemptsForPhone($phone, $minutes) >= $maxAttempts;
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    /**
     * Risk darajasi: low / medium / high / critical
     */
    public function getRiskLevelAttribute(): string
    {
        return match (true) {
            $this->risk_score >= 80 => 'critical',
            $this->risk_score >= 60 => 'high',
            $this->risk_score >= 30 => 'medium',
            default                 => 'low',
        };
    }
}
