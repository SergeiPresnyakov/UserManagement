<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAuthRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register()
    {
        return view('register');
    }

    public function login()
    {
        return view('login');
    }

    /**
     * Register user
     * 
     * @param App\Http\Requests\UserRegisterRequest $request
     */
    public function create(UserRegisterRequest $request)
    {
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        UserInfo::create(['user_id' => $user->id]);

        return redirect()
            ->route('login')
            ->with('success', 'Регистрация прошла успешно');
    }

    /**
     * Authenticate user
     * 
     * @param App\Http\Requests\UserAuthRequest $request
     */
    public function auth(UserAuthRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $rememberMe = $request->remember == 'on' ? true : false;

        if (Auth::attempt($credentials, $rememberMe)) {
            $request->session()->regenerate();
            
            return redirect()
                ->route('index');
        }

        return back()
            ->withErrors('Неверный email или пароль');
    }
}
