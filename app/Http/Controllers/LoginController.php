<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function tryLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->get('username');
        $password = $request->get('password');

        try {
            if (Auth::attempt(['username' => $username, 'password' => $password, 'deleted_at' => null])) {
                return response()->json(['status' => 'success']);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Username atau Password salah!'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'failed',
                'message' => $th->getMessage(),
                'details' => $th
            ]);
        }
    }
}
