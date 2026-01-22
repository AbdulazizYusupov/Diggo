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
    ];

    protected $casts = [
        'successful' => 'boolean',
        'attempted_at' => 'datetime',
    ];

    /**
     * Scope for failed attempts
     */
    public function scopeFailed($query)
    {
        return $query->where('successful', false);
    }

    /**
     * Scope for successful attempts
     */
    public function scopeSuccessful($query)
    {
        return $query->where('successful', true);
    }

    /**
     * Scope for recent attempts (within last X minutes)
     */
    public function scopeRecent($query, int $minutes = 5)
    {
        return $query->where('attempted_at', '>=', now()->subMinutes($minutes));
    }

    /**
     * Scope for specific phone
     */
    public function scopeForPhone($query, string $phone)
    {
        return $query->where('phone', $phone);
    }

    /**
     * Scope for specific IP
     */
    public function scopeForIp($query, string $ip)
    {
        return $query->where('ip_address', $ip);
    }

    /**
     * Get recent failed attempts count for a phone/IP combination
     */
    public static function getRecentFailedAttempts(string $phone, string $ip, int $minutes = 5): int
    {
        return static::failed()
            ->forPhone($phone)
            ->forIp($ip)
            ->recent($minutes)
            ->count();
    }

    /**
     * Clean old login attempts (older than 30 days)
     */
    public static function cleanOldAttempts(): int
    {
        return static::where('attempted_at', '<', now()->subDays(30))->delete();
    }
}
