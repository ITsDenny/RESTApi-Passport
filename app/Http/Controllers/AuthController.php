<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request -> validate([
            'username' => 'required',            
            'password' => 'required'            
        ]);

        if(Auth::attempt(['username' => $request->input('username'), 'password' => $request->input('password')])){
            $token = auth()->user()->createToken('authToken')->accessToken;
            return response()->json([
                'token' => $token
            ], 200);
            
        }else{
            return response()->json([
                'Message' => 'Invalid!'
            ], 401);
            
        }
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        //Kembalikan Message json ketika berhasil logout
        return response()->json([
            'Message' => 'Logout successfully!'
        ], 200);
    }
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required | unique:user',
            'password' => 'required | min : 6',
            'firstName' => 'required | string |max:50 ',
            'lastName' => 'required | string |max:50 ',
            'DateOfBirth' => 'required | date',
            'phone_number' => 'required | string | max:15'
        ]);

        //Method user baru

        $user = new user([
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
            'firstName' => $request->input('firstName'),
            'lastName' => $request->input('lastName'),
            'DateOfBirth' => $request->input('DateOfBirth'),
            'phone_number' => $request->input('phone_number')
        ]);

        $user -> save();
        //Membuat token register
        $token = $user->createToken('authToken')->accessToken;

        return response()->json(['token' => $token], 200);

    }

   

    
    /*
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Metode untuk menampilkan formulir login
    public function showLoginForm()
    {
        return view('auth.login');
    }
    */
}
