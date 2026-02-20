<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;

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
    protected $redirectTo = '/home';

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

    public function handleOTP($user)
    {
        $otp = rand(100000, 999999);

        $user->update([
            'otp' => $otp
        ]);

        // Kirim email
        Mail::raw("Kode OTP Anda adalah: $otp", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Kode OTP Login');
        });

        session(['otp_user_id' => $user->id]);

        return redirect()->route('otp.form');
    }

    protected function authenticated(Request $request, $user)
    {
        // Logout dulu supaya belum benar-benar login
        Auth::logout();

        return $this->handleOTP($user);
    }

    public function showOTP()
    {
        return view('auth.otp');
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = User::find(session('otp_user_id'));

        if (!$user || strtoupper($user->otp) !== strtoupper($request->otp)) {
            return back()->withErrors(['otp' => 'Kode OTP salah.']);
        }

        // OTP benar, login user
        Auth::login($user);

        // hapus otp
        $user->update(['otp' => null]);
        session()->forget('otp_user_id');

        return redirect('/home');
    }

}
