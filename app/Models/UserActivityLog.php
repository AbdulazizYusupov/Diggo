<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'user_agent',
        'device',
        'browser',
        'platform',
        'logged_at',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper: browser va device aniqlash
    public static function parseUserAgent(string $userAgent): array
    {
        $browser = 'Unknown';
        $platform = 'Unknown';
        $device = 'Desktop';

        // Browser
        if (str_contains($userAgent, 'Chrome') && !str_contains($userAgent, 'Edg')) $browser = 'Chrome';
        elseif (str_contains($userAgent, 'Firefox')) $browser = 'Firefox';
        elseif (str_contains($userAgent, 'Safari') && !str_contains($userAgent, 'Chrome')) $browser = 'Safari';
        elseif (str_contains($userAgent, 'Edg')) $browser = 'Edge';
        elseif (str_contains($userAgent, 'Opera') || str_contains($userAgent, 'OPR')) $browser = 'Opera';

        // Platform
        if (str_contains($userAgent, 'Windows')) $platform = 'Windows';
        elseif (str_contains($userAgent, 'Mac')) $platform = 'macOS';
        elseif (str_contains($userAgent, 'Linux')) $platform = 'Linux';
        elseif (str_contains($userAgent, 'Android')) $platform = 'Android';
        elseif (str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) $platform = 'iOS';

        // Device
        if (str_contains($userAgent, 'Mobile') || str_contains($userAgent, 'Android') || str_contains($userAgent, 'iPhone')) {
            $device = 'Mobile';
        } elseif (str_contains($userAgent, 'iPad') || str_contains($userAgent, 'Tablet')) {
            $device = 'Tablet';
        }

        return compact('browser', 'platform', 'device');
    }
}
