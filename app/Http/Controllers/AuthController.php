<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function dataUser(){
        $data = User::all();
        return response()->json($data, 200);
    }

    public function login(Request $request){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $response['status'] = true;
            $response['message'] = 'Berhasil login';
            $response['name'] = $user->name;
            $response['email'] = $user->email;
            $response['data']['token'] = 'Bearer ' . $user->createToken('TestAPI')->accessToken;
            return response()->json($response, 200);
        }else{
            $response['status'] = false;
            $response['message'] = 'Unauthorized';
            return response()->json($response, 402);
        }
    }

    public function register(Request $request){
        $validated = $request->validate([
            'name' => 'required|max:60',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|same:password_confirmation',
            'password_confirmation' => 'required'
        ]);

        if(!$validated){
            $response['status'] = false;
            $response['message'] = 'Registrasi Gagal';
            $response['error'] = $validated;
            return response()->json($response, 422);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $response['status'] = true;
        $response['message'] = 'Registrasi Berhasil';
        return response()->json($response, 200);
    }

    public function logout(Request $request){

        $request->user()->token()->revoke();
        $response['status'] = true;
        $response['message'] = 'Berhasil logout';

        return response()->json($response, 200);
    }
}
