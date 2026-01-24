<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function index()
    {
        $path = storage_path('app/public/Login');

        // Agar papka mavjud bo'lsa, fayllarni massivga olamiz
        $files = File::exists($path) ? File::files($path) : [];

        // Random bitta rasm nomini tanlash
        $randomImage = 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1920&q=80'; // Default rasm

        if (count($files) > 0) {
            $file = $files[array_rand($files)];
            // Fayl nomini olamiz (masalan: rasm1.jpg)
            $randomImage = asset('storage/Login/' . $file->getFilename());
        }
        return view('Auth.login',compact('randomImage'));
    }
    public function login(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'min:9'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $credentials = $request->only('phone', 'password');
        $credentials['is_active'] = 1;

        if (Auth::attempt($credentials, $request->filled('remember_me'))) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'phone' => 'Telefon raqam yoki parol noto‘g‘ri',
        ])->onlyInput('phone');
    }

    public function login1(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'min:9'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        // Check if too many login attempts
        $key = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'phone' => [
                    "Juda ko'p urinish. Iltimos, " . ceil($seconds / 60) . " daqiqadan so'ng qayta urinib ko'ring."
                ],
            ]);
        }

        $credentials = $request->only('phone', 'password');
        $credentials['is_active'] = 1;

        if (Auth::attempt($credentials, $request->filled('remember_me'))) {
            $request->session()->regenerate();

            // Clear the rate limiter on successful login
            RateLimiter::clear($key);

            // Log successful login
            $this->logLoginAttempt($request, true);

            return redirect()->route('dashboard');
        }

        // Increment failed attempts
        RateLimiter::hit($key, 300); // 300 seconds = 5 minutes

        // Log failed login
        $this->logLoginAttempt($request, false);

        return back()->withErrors([
            'phone' => 'Telefon raqam yoki parol noto\'g\'ri. Qolgan urinishlar: ' . (5 - RateLimiter::attempts($key)),
        ])->onlyInput('phone');
    }
    protected function throttleKey(Request $request): string
    {
        return strtolower($request->input('phone')) . '|' . $request->ip();
    }

    /**
     * Log login attempt to database
     */
    protected function logLoginAttempt(Request $request, bool $successful): void
    {
        LoginAttempt::create([
            'phone' => $request->input('phone'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'successful' => $successful,
            'attempted_at' => now(),
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
