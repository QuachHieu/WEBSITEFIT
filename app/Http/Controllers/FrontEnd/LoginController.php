<?php

namespace App\Http\Controllers\FrontEnd;

use App\Consts;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Socialite;
use App\Models\User;

class LoginController extends Controller
{

    public function index()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('frontend.home');
        }
        return redirect()->route('frontend.home');
        //return view('frontend.pages.login');
    }

    public function login(LoginRequest $request)
    {

        $url = $request->input('url') ?? route('frontend.home');

        $email = $request->email;
        $password = $request->password;

        $attempt = Auth::guard('admin')->attempt([
            'email' => $email,
            'password' => $password,
            'status' => Consts::USER_STATUS['active']
        ]);
        
        // dd(Auth::guard('admin'));

        if (Auth::guard('admin')->check()) {
            return redirect()->route('frontend.home');
        }

        if ($attempt) {

            return redirect($url);
        }

        return redirect()->back()->with(
            'errorMessage',
            'Wrong credential! Please try again!'
        );
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect()->back();
    }

    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback() {
        try {
            $user = Socialite::driver('google')->user();
            $finduser = User::where('username', $user->id)->first();
            if ($finduser) {
                Auth::login($finduser);
                return redirect('/');
            } else {
                $newUser = User::create(['name' => $user->name, 'email' => $user->email, 'username' => $user->id]);
                Auth::login($newUser);
                return redirect()->back();
            }
        }
        catch(Exception $e) {
            return redirect('auth/google');
        }
    }

    public function redirectToFacebook() {
        return Socialite::driver('google')->redirect();
    }
    public function handleFacebookCallback() {
        try {
            $user = Socialite::driver('facebook')->user();
            $finduser = User::where('username', $user->id)->first();
            if ($finduser) {
                Auth::login($finduser);
                return redirect('/');
            } else {
                $newUser = User::create(['name' => $user->name, 'email' => $user->email, 'username' => $user->id]);
                Auth::login($newUser);
                return redirect()->back();
            }
        }
        catch(Exception $e) {
            return redirect('auth/facebook');
        }
    }

}
