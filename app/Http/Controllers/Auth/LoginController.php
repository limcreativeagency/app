<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $password = $request->input('password');
        $login = null;
        $fieldType = null;

        if ($request->filled('phone')) {
            $login = $request->input('phone');
            $fieldType = 'phone';
        } elseif ($request->filled('email')) {
            $login = $request->input('email');
            $fieldType = 'email';
        }

        $user = \App\Models\User::where($fieldType, $login)->first();
        if (!$user) {
            return back()->withErrors([$fieldType => 'Kullanıcı bulunamadı: ' . $login])->withInput();
        }
        if (!\Hash::check($password, $user->password)) {
            return back()->withErrors([$fieldType => 'Şifre yanlış'])->withInput();
        }

        auth()->login($user, $request->filled('remember'));
        $request->session()->regenerate();
        return $this->authenticated($request, $user) ?: redirect()->intended($this->redirectPath());
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
            'phone' => 'required_without:email|string',
            'email' => 'required_without:phone|email',
        ]);
    }

    protected function authenticated($request, $user)
    {
        switch ($user->role_id) {
            case 1:
                return redirect('/admin');
            case 2:
                return redirect('/clinic/dashboard');
            default:
                return redirect('/dashboard');
        }
    }

    protected function redirectPath()
    {
        return '/dashboard';
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }
}