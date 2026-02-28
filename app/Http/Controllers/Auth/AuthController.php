<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    private const MAX_ATTEMPTS_PER_PHONE = 10;  // 1 soatda phone bo'yicha
    private const MAX_ATTEMPTS_PER_IP    = 20;  // 1 soatda IP bo'yicha
    private const RATE_LIMIT_ATTEMPTS    = 5;   // RateLimiter uchun (qisqa oyna)
    private const RATE_LIMIT_DECAY       = 300; // 5 daqiqa (sekund)
    private const BLOCK_WINDOW_MINUTES   = 60;

    public function index()
    {
        // 1. Papka manzili: public/asset/Login
        $path = public_path('asset/Login');

        // Default rasm (zaxira uchun)
        $randomImage = 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1920&q=80';

        // 2. Papka borligini va ichida fayllar borligini tekshirish
        if (File::isDirectory($path)) {
            // Faqat rasm formatidagi fayllarni olish (xavfsizroq yo'li)
            $files = File::glob($path . '/*.{jpg,jpeg,png,gif,webp,svg}', GLOB_BRACE);

            if (count($files) > 0) {
                // 3. Random bitta fayl yo'lini tanlash
                $randomFilePath = $files[array_rand($files)];

                // 4. basename() orqali faqat fayl nomini ajratib olamiz (masalan: rasm.jpg)
                $fileName = basename($randomFilePath);

                // 5. URL: http://sayt.uz/asset/Login/rasm.jpg
                $randomImage = asset('asset/Login/' . $fileName);
            }
        }
        return view('Auth.login', compact('randomImage'));
    }
    //bu ishlaydigan asli edi
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'phone' => ['required', 'string', 'min:9'],
    //         'password' => ['required', 'string', 'min:6'],
    //     ]);
    //     $credentials = $request->only('phone', 'password');
    //     $credentials['is_active'] = 1;

    //     if (Auth::attempt($credentials, $request->filled('remember'))) {
    //         $request->session()->regenerate();
    //         $this->logActivity($request, 'login');
    //         if (Auth::user()->hasRole('director')) {
    //             return redirect()->route('director.dashboard');
    //         } elseif (Auth::user()->hasRole('student')) {
    //             return redirect()->route('student.dashboard');
    //         } elseif (Auth::user()->hasRole('teacher')) {
    //             return redirect()->route('teacher.dashboard');
    //         }

    //         return redirect()->route('dashboard');
    //     }

    //     return back()->withErrors([
    //         'phone' => 'These credentials do not match our records.',
    //     ])->onlyInput('phone');
    // }

    public function login(Request $request)
    {
        $request->validate([
            'phone'    => ['required', 'string', 'min:9'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $phone = $request->input('phone');
        $ip    = $request->ip();
        $key   = $this->throttleKey($request);

        // 1. IP bloklangan ekanini tekshir
        if (LoginAttempt::shouldBlockIp($ip, self::MAX_ATTEMPTS_PER_IP, self::BLOCK_WINDOW_MINUTES)) {
            $this->logLoginAttempt($request, false, 'rate_limited', true);
            return back()->withErrors([
                'phone' => 'Bu IP manzil vaqtincha bloklangan. Iltimos, keyinroq urinib ko\'ring.',
            ])->onlyInput('phone');
        }

        // 2. Phone bloklangan ekanini tekshir
        if (LoginAttempt::shouldBlockPhone($phone, self::MAX_ATTEMPTS_PER_PHONE, self::BLOCK_WINDOW_MINUTES)) {
            $this->logLoginAttempt($request, false, 'rate_limited', true);
            return back()->withErrors([
                'phone' => 'Bu raqam vaqtincha bloklangan. ' . self::BLOCK_WINDOW_MINUTES . ' daqiqadan so\'ng urinib ko\'ring.',
            ])->onlyInput('phone');
        }

        // 3. RateLimiter — tez-tez urinishlardan himoya
        if (RateLimiter::tooManyAttempts($key, self::RATE_LIMIT_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($key);
            $this->logLoginAttempt($request, false, 'rate_limited', true);
            return back()->withErrors([
                'phone' => 'Juda ko\'p urinish. Iltimos, ' . ceil($seconds / 60) . ' daqiqadan so\'ng qayta urinib ko\'ring.',
            ])->onlyInput('phone');
        }

        // 4. Foydalanuvchi mavjudligini tekshir
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            RateLimiter::hit($key, self::RATE_LIMIT_DECAY);
            $this->logLoginAttempt($request, false, 'user_not_found');
            return $this->failedResponse($request, $key);
        }

        // if (!$user->is_active) {
        //     RateLimiter::hit($key, self::RATE_LIMIT_DECAY);
        //     $this->logLoginAttempt($request, false, 'user_inactive');
        //     return back()->withErrors([
        //         'phone' => 'Bu hisob o\'chirilgan. Administrator bilan bog\'laning.',
        //     ])->onlyInput('phone');
        // }
        if (!$user->is_active) {
            RateLimiter::hit($key, self::RATE_LIMIT_DECAY);
            $this->logLoginAttempt($request, false, 'user_inactive');
            return $this->failedResponse($request, $key);
        }

        // 5. Auth::attempt — login va login kabi role-based redirect
        $credentials = $request->only('phone', 'password');
        $credentials['is_active'] = 1;

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            RateLimiter::clear($key);

            // Muvaffaqiyatli login logl
            $this->logLoginAttempt($request, true, null, false, Auth::id());
            $this->logActivity($request, 'login');

            // login() dagi aynan bir xil role-based redirect
            if (Auth::user()->hasRole('director')) {
                return redirect()->route('director.dashboard');
            } elseif (Auth::user()->hasRole('student')) {
                return redirect()->route('student.dashboard');
            } elseif (Auth::user()->hasRole('teacher')) {
                return redirect()->route('teacher.dashboard');
            }

            return redirect()->route('dashboard');
        }

        // 6. Parol noto'g'ri
        RateLimiter::hit($key, self::RATE_LIMIT_DECAY);
        $this->logLoginAttempt($request, false, 'wrong_password');
        return $this->failedResponse($request, $key);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $this->logActivity($request, 'logout');
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
    protected function throttleKey(Request $request): string
    {
        return strtolower($request->input('phone')) . '|' . $request->ip();
    }

    /**
     * Xato login javobi — qolgan urinishlar sonini ko'rsatadi
     */
    private function failedResponse(Request $request, string $key)
    {
        $remaining = max(0, self::RATE_LIMIT_ATTEMPTS - RateLimiter::attempts($key));
        $message   = 'Telefon raqam yoki parol noto\'g\'ri.';

        if ($remaining <= 2 && $remaining > 0) {
            $message .= " Diqqat: yana {$remaining} ta urinish qoldi.";
        } elseif ($remaining === 0) {
            $message .= ' Hisob vaqtincha bloklandi.';
        }

        return back()->withErrors(['phone' => $message])->onlyInput('phone');
    }

    /**
     * LoginAttempt yozuvini saqlash
     */
    protected function logLoginAttempt(
        Request $request,
        bool    $successful,
        ?string $failureReason = null,
        bool    $blocked       = false,
        ?int    $userId        = null
    ): void {
        $ua     = $request->userAgent() ?? '';
        $parsed = UserActivityLog::parseUserAgent($ua);
        $phone  = $request->input('phone');
        $ip     = $request->ip();

        // Risk score faqat xato urinishlarda hisoblanadi
        $riskScore = $successful ? 0 : LoginAttempt::calculateRiskScore($phone, $ip);

        LoginAttempt::create([
            'phone'          => $phone,
            'ip_address'     => $ip,
            'user_agent'     => $ua,
            'successful'     => $successful,
            'blocked'        => $blocked,
            'failure_reason' => $failureReason,
            'user_id'        => $userId,
            'browser'        => $parsed['browser'],
            'platform'       => $parsed['platform'],
            'device'         => $parsed['device'],
            'risk_score'     => $riskScore,
            'attempted_at'   => now(),
        ]);
    }

    /**
     * UserActivityLog ga login/logout yozish
     */
    protected function logActivity(Request $request, string $action): void
    {
        $ua     = $request->userAgent() ?? '';
        $parsed = UserActivityLog::parseUserAgent($ua);

        UserActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => $action,
            'ip_address' => $request->ip(),
            'user_agent' => $ua,
            'browser'    => $parsed['browser'],
            'platform'   => $parsed['platform'],
            'device'     => $parsed['device'],
            'logged_at'  => now(),
        ]);
    }
}
