<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        if (auth()->user()->utype === 'ADM') {
            return '/admin';
        }
        return '/account-dashboard';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function username()
    {
        return 'login'; // Trường name của input form: <input name="login">
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $login_type = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $credentials = [
            $login_type => $request->input('login'),
            'password' => $request->input('password'),
        ];

        if (\Auth::guard('web')->attempt($credentials)) {
            $user = \Auth::user();
            if ($user->utype === 'USR' && (empty($user->phone) || $user->phone == '')) {
                return redirect()->route('users.edit', $user->id)->with('status', 'Vui lòng cập nhật số điện thoại để tiếp tục sử dụng tài khoản!');
            }
            return redirect()->intended($this->redirectTo());
        }

        return back()->withErrors([
            'login' => 'Thông tin đăng nhập không đúng!'
        ]);
    }
}
