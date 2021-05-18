<?php

namespace App\Services\Admin;

use App\Helpers\DataEncryptHelper;
use App\Models\User;
use App\Models\UserProfile;
use App\Repositories\Admin\ProfileRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Telegram\Bot\Api;

class ProfileService
{
    private $repository;
    private $dataEncrypt;

    public function __construct(ProfileRepository $repository,DataEncryptHelper $dataEncrypt)
    {
        $this->repository = $repository;
        $this->dataEncrypt = $dataEncrypt;
    }

    public function getUserInfo($user_id)
    {
        return $this->repository->userInfo($user_id);
    }

    public function encryptString($string)
    {
        return $this->dataEncrypt->encryptString($string);
    }

    public function decryptString($string)
    {
        return $this->dataEncrypt->decryptString($string);
    }

    public function indexData()
    {
        $user_id = Auth::id();
        $profile = $this->checkProfile();

        return [
            'user_info' => $this->getUserInfo($user_id),
            'profile_id' => $this->encryptString($profile->id),
            'profile' => $profile
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

    public function checkProfile()
    {
        $profile = UserProfile::query()->where('user_id','=',Auth::id())->first();

        if ($profile == null) {
            DB::beginTransaction();
            $data = new UserProfile();
            $data->user_id = Auth::id();
            $data->save();
            DB::commit();

            return $data;
        } else {
            return $profile;
        }
    }

    public function storeProfile($username,$name)
    {
        $this->checkProfile();
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
        $this->checkProfile();
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

    public function sendTestMessage()
    {
        try {
            $profile = UserProfile::query()->where('user_id','=',Auth::id())->first();

            $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
            $telegram->sendMessage([
                'chat_id' => $profile->telegram_chat_id,
                'parse_mode' => 'html',
                'text' => "<pre>Ini adalah pesan TEST!</pre>",
            ]);

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
