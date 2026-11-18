<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /** Admin/petugas -> area admin, pasien -> area pasien. */
    private function home(User $user): string
    {
        return $user->isStaff() ? '/admin/layar-tv' : '/katalog';
    }

    /** Pasien kembali ke halaman yang tadi dituju (url.intended); staf selalu ke area admin. */
    private function after(User $user, Request $request): string
    {
        $intended = $request->session()->pull('url.intended');
        $path = $intended ? (parse_url($intended, PHP_URL_PATH) ?: '') : '';
        if ($user->isStaff() || !$path || $path === '/login' || str_starts_with($path, '/api') || str_starts_with($path, '/admin')) {
            return $this->home($user);
        }
        return $path;
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect($this->home(Auth::user()));
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $cred = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($cred, $request->boolean('remember'))) {
            return response()->json(['message' => 'Email atau password salah.'], 422);
        }

        $request->session()->regenerate();
        return ['redirect' => $this->after(Auth::user(), $request)];
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => ['required', 'regex:/^(\+62|62|0)8[0-9]{7,12}$/'],
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:6',
        ], [
            'phone.regex' => 'Nomor HP tidak valid (contoh: 081234567890).',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'pasien',
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        return ['redirect' => $this->after($user, $request)];
    }

    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback(Request $request)
    {
        try {
            $g = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect('/login?error=google');
        }

        $user = User::where('google_id', $g->getId())->orWhere('email', $g->getEmail())->first();

        if ($user) {
            if (!$user->google_id) {
                $user->update(['google_id' => $g->getId()]);
            }
        } else {
            $user = User::create([
                'name' => $g->getName() ?: 'Pasien',
                'email' => $g->getEmail(),
                'google_id' => $g->getId(),
                'password' => Hash::make(Str::random(32)),
                'role' => 'pasien',
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();
        return redirect($this->after($user, $request));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return ['redirect' => '/'];
    }
}
