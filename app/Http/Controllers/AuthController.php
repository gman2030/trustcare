<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showForm()
    {
        return view('login-sign-up');
    }


    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return redirect()->route('login.view')->with('success', 'Account created! Please login.');
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);



        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();


            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            if ($user->role === 'worker') {
                return redirect()->route('worker.dashboard');
            }
            if ($user->role === 'supply') {
                return redirect()->route('supply.dashboard');
            }
            return redirect()->intended('/home');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.view');
    }
}
