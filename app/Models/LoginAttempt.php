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
        'user_id',
        'blocked',
        'failure_reason',
        'browser',
        'platform',
        'device',
        'risk_score',
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
     * Phone uchun oxirgi muvaffaqiyatli login vaqtini qaytaradi.
     * Ichki yordamchi — takrorlanishni oldini oladi.
     */
    private static function lastSuccessTimeForPhone(string $phone): ?string
    {
        return static::where('phone', $phone)
            ->where('successful', true)
            ->latest('attempted_at')
            ->value('attempted_at');
    }

    /**
     * IP uchun oxirgi muvaffaqiyatli login vaqtini qaytaradi.
     */
    private static function lastSuccessTimeForIp(string $ip): ?string
    {
        return static::where('ip_address', $ip)
            ->where('successful', true)
            ->latest('attempted_at')
            ->value('attempted_at');
    }

    /**
     * Phone bo'yicha xato urinishlar soni — oxirgi muvaffaqiyatli logindan KEYIN.
     *
     * Misol:
     *   3 xato → login ✅ → logout → 7 xato
     *   DB da jami 10 xato, lekin natija = 7  (blok bo'lmaydi)
     *
     * Agar hech qachon muvaffaqiyatli login bo'lmagan bo'lsa — barcha xatolar sanaladi.
     */
    public static function getFailedAttemptsForPhone(string $phone, int $minutes = 60): int
    {
        $lastSuccess = static::lastSuccessTimeForPhone($phone);

        return static::where('phone', $phone)
            ->where('successful', false)
            ->where('attempted_at', '>=', now()->subMinutes($minutes))
            ->when($lastSuccess, fn($q) => $q->where('attempted_at', '>', $lastSuccess))
            ->count();
    }

    /**
     * IP bo'yicha xato urinishlar soni — shu IPdan oxirgi muvaffaqiyatli logindan KEYIN.
     */
    public static function getFailedAttemptsForIp(string $ip, int $minutes = 60): int
    {
        $lastSuccess = static::lastSuccessTimeForIp($ip);

        return static::where('ip_address', $ip)
            ->where('successful', false)
            ->where('attempted_at', '>=', now()->subMinutes($minutes))
            ->when($lastSuccess, fn($q) => $q->where('attempted_at', '>', $lastSuccess))
            ->count();
    }

    /**
     * Berilgan phone bloklanishi kerakmi?
     */
    public static function shouldBlockPhone(string $phone, int $maxAttempts = 10, int $minutes = 60): bool
    {
        return static::getFailedAttemptsForPhone($phone, $minutes) >= $maxAttempts;
    }

    /**
     * Berilgan IP bloklanishi kerakmi?
     */
    public static function shouldBlockIp(string $ip, int $maxAttempts = 20, int $minutes = 60): bool
    {
        return static::getFailedAttemptsForIp($ip, $minutes) >= $maxAttempts;
    }

    /**
     * Risk score hisoblash (0–100).
     *
     * Bitta query, PHP da hisoblash.
     * Oxirgi muvaffaqiyatli logindan keyingi xatolar hisobga olinadi.
     *
     * Ball taqsimoti:
     *   5 daqiqa ichida xatolar  → max 45 ball
     *   1 soat ichida xatolar    → max 30 ball
     *   IP diversity (stuffing)  → max 25 ball
     */
    public static function calculateRiskScore(string $phone, string $ip): int
    {
        $lastSuccess = static::lastSuccessTimeForPhone($phone);

        $attempts = static::where('successful', false)
            ->where(fn($q) => $q->where('phone', $phone)->orWhere('ip_address', $ip))
            ->where('attempted_at', '>=', now()->subMinutes(60))
            ->when($lastSuccess, fn($q) => $q->where('attempted_at', '>', $lastSuccess))
            ->select(['attempted_at', 'ip_address', 'phone'])
            ->get();

        $recent5   = $attempts->where('attempted_at', '>=', now()->subMinutes(5))->count();
        $recent60  = $attempts->count();
        $ipVariety = $attempts->where('ip_address', $ip)->pluck('phone')->unique()->count();

        $score  = min($recent5   * 15, 45);
        $score += min($recent60  * 5,  30);
        $score += min($ipVariety * 10, 25);

        return min($score, 100);
    }

    /**
     * So'nggi X daqiqada phone yoki IP bo'yicha umumiy xato urinishlar
     * (blok tekshiruvisiz, tezkor nazorat uchun)
     */
    public static function getRecentFailedAttempts(string $phone, string $ip, int $minutes = 15): int
    {
        return static::where('successful', false)
            ->where(fn($q) => $q->where('phone', $phone)->orWhere('ip_address', $ip))
            ->where('attempted_at', '>=', now()->subMinutes($minutes))
            ->count();
    }

    /**
     * Eski yozuvlarni o'chirish
     */
    public static function cleanOldAttempts(int $days = 30): int
    {
        return static::where('attempted_at', '<', now()->subDays($days))->delete();
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

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