<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Repositories\Admin\ProfileRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    private $repository;

    public function __construct(ProfileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getUserInfo($user_id)
    {
        return $this->repository->userInfo($user_id);
    }

    public function indexData()
    {
        $user_id = Auth::id();

        return [
            'user_info' => $this->getUserInfo($user_id)
        ];
    }

    public function usernameExists($username)
    {
        $user = User::query()
            ->where('username','=',$username)
            ->whereNotIn('id',[Auth::id()]);

        if ($user->exists()) {
            return true;
        } else {
            return false;
        }
    }

    public function storeProfile($username,$name)
    {
        try {
            if ($this->usernameExists($username) == true) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Username sudah digunakan'
                ]);
            } else {
                DB::beginTransaction();
                $user = User::find(Auth::id());
                $user->username = $username;
                $user->name = $name;
                $user->save();
                DB::commit();

                return response()->json([
                    'status' => 'success'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => [
                    $th->getFile(),
                    $th->getLine()
                ]
            ]);
        }
    }

    public function storeNewPassword($old_password,$new_password,$repeated_new_password)
    {
        try {
            $stored_old_password = Auth::user()->getAuthPassword();

            if (!Hash::check($old_password,$stored_old_password))
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Password Lama salah!'
                ]);

            if ($new_password !== $repeated_new_password)
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Password Baru dan Perulangan berbeda!'
                ]);

            DB::beginTransaction();

            $data = User::find(Auth::id());
            $data->password = Hash::make($new_password);
            $data->save();

            DB::commit();

            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => [
                    $th->getFile(),
                    $th->getLine()
                ]
            ],500);
        }
    }
}
