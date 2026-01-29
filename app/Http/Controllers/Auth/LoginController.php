<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            Log::channel('auth')->info('Login form accessed by authenticated user', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now(),
            ]);
            return redirect('/portal/home')->with('info', 'Anda masih login, pastikan logout untuk login akun lain.');
        }

        Log::channel('auth')->info('Login form accessed', [
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);

        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $this->preventShellInjection($request);

        Log::channel('auth')->info('Login attempt started', [
            'email' => $request->input('email'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
        ]);

        $this->ensureIsNotRateLimited($request);

        $credentials = $request->validate(
            [
                'email' => ['required', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:3'],
            ],
            [
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.max' => 'Email maksimal 255 karakter.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 3 karakter.',
            ],
        );

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            Log::channel('auth')->warning('Login failed - user not found', [
                'email' => $credentials['email'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);
            RateLimiter::hit($this->throttleKey($request));
            return back()->with('error', 'Email atau password salah. Silakan coba lagi.');
        }

        if ($user->status !== 'active') {
            Log::channel('auth')->warning('Login failed - inactive user', [
                'user_id' => $user->id,
                'email' => $user->email,
                'status' => $user->status,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);
            return back()->with('error', 'Akun Anda tidak aktif. Silakan hubungi admin.');
        }

        $remember = $request->boolean('remember-me');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            Log::channel('auth')->info('Login successful', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'remember' => $remember,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);

            RateLimiter::clear($this->throttleKey($request));

            return redirect()->intended('/portal/home')->with('success', 'Login berhasil! Selamat datang kembali.');
        }

        Log::channel('auth')->warning('Login failed - invalid credentials', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
        ]);

        RateLimiter::hit($this->throttleKey($request));

        return back()->with('error', 'Email atau password salah. Silakan coba lagi.');
    }

    protected function ensureIsNotRateLimited(Request $request)
    {
        $maxAttempts = 5;

        if (RateLimiter::tooManyAttempts($this->throttleKey($request), $maxAttempts)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($request));
            $minutes = ceil($seconds / 60);

            Log::channel('auth')->warning('Login rate limited', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'wait_minutes' => $minutes,
                'timestamp' => now(),
            ]);

            return back()
                ->with('warning', "Terlalu banyak percobaan login. Tunggu {$minutes} menit sebelum mencoba lagi.")
                ->onlyInput('email');
        }
    }

    protected function preventShellInjection(Request $request)
    {
        $safeFields = ['_token', '_method', 'remember-me'];
        
        $dangerousPatterns = [
            '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/',
            '/\b(exec|system|shell_exec|passthru|eval|base64_decode|file_get_contents|fopen|fwrite|include|require)\s*\(/i',
            '/\b(cmd|powershell|bash|sh|zsh|fish)\s+/i',
            '/\b(rm|del|format|fdisk|mkfs)\s+/i',
            '/\b(wget|curl|nc|netcat|telnet|ssh|ftp)\s+/i',
            '/\b(python|perl|ruby|php|node|java)\s+/i',
            '/\.\.\//i',
            '/\/etc\//i',
            '/\/bin\//i',
            '/\/usr\//i',
            '/\/var\//i',
            '/\/tmp\//i',
            '/\/proc\//i',
            '/\/dev\//i',
            '/\/sys\//i',
            '/;\s*(rm|del|format|exec|system|shell_exec)/i',
            '/\|\s*(rm|del|format|exec|system|shell_exec)/i',
            '/&&\s*(rm|del|format|exec|system|shell_exec)/i',
            '/`[^`]*`/i',
            '/\$\([^)]*\)/i',
        ];

        $inputs = [
            'email' => $request->input('email', ''),
            'password' => $request->input('password', ''),
        ];

        foreach ($inputs as $field => $value) {
            if (is_string($value) && !in_array($field, $safeFields)) {
                foreach ($dangerousPatterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        Log::channel('auth')->critical('Shell injection attempt detected', [
                            'field' => $field,
                            'value' => $value,
                            'pattern' => $pattern,
                            'ip' => $request->ip(),
                            'user_agent' => $request->userAgent(),
                            'timestamp' => now(),
                        ]);
                        
                        RateLimiter::hit($this->throttleKey($request), 3600);
                        
                        abort(403, 'Forbidden');
                    }
                }
            }
        }

        $userAgent = $request->userAgent();
        $suspiciousAgents = [
            '/curl\/[0-9]/i',
            '/wget\/[0-9]/i',
            '/python-requests/i',
            '/perl/i',
            '/ruby/i',
            '/java\/[0-9]/i',
            '/node\/[0-9]/i',
            '/php\/[0-9]/i',
            '/sqlmap/i',
            '/nikto/i',
            '/nmap/i',
            '/masscan/i',
        ];

        foreach ($suspiciousAgents as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                Log::channel('auth')->warning('Suspicious user agent detected', [
                    'user_agent' => $userAgent,
                    'ip' => $request->ip(),
                    'timestamp' => now(),
                ]);
                
                RateLimiter::hit($this->throttleKey($request), 1800);
                
                abort(403, 'Forbidden');
            }
        }
    }

    protected function throttleKey(Request $request)
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }

    public function clearRateLimit(Request $request)
    {
        if (config('app.env') === 'local' || config('app.debug')) {
            $key = $this->throttleKey($request);
            RateLimiter::clear($key);
            
            Log::channel('auth')->info('Rate limit cleared', [
                'key' => $key,
                'ip' => $request->ip(),
                'timestamp' => now(),
            ]);
            
            return response()->json(['message' => 'Rate limit cleared']);
        }
        
        return response()->json(['message' => 'Not allowed'], 403);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        Log::channel('auth')->info('Logout initiated', [
            'user_id' => $user ? $user->id : null,
            'email' => $user ? $user->email : null,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::channel('auth')->info('Logout completed', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
        ]);

        return redirect('/portal/login')->with('success', 'Anda telah logout dari sistem.');
    }
}
