<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Notifications\StoreRequisitionCreated;
use App\Services\Admin\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Telegram\Bot\Api;

class ProfileController extends Controller
{
    private $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.profile.index',$this->service->indexData());
    }

    public function storeProfile(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'name' => 'required',
        ]);

        $username = $request->get('username');
        $name = $request->get('name');

        return $this->service->storeProfile($username,$name);
    }

    public function storePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'repeated_new_password' => 'required',
        ]);

        $old_password = $request->get('old_password');
        $new_password = $request->get('new_password');
        $repeated_new_password = $request->get('repeated_new_password');

        return $this->service->storeNewPassword($old_password,$new_password,$repeated_new_password);
    }

    public function generateTelegram(Request $request)
    {
        try {
            $message = $request->get('message');
            $profile_id = $this->service->decryptString(str_replace('/start ','',$message['text']));

            $data = UserProfile::find($profile_id);
            $data->telegram_user_id = $message['chat']['username'];
            $data->telegram_chat_id = $message['chat']['id'];
            $data->telegram_response = json_encode([
                $request->all()
            ]);
            $data->save();

            $user = User::find($data->user_id);

            $time = date('H');
            switch ($time) {
                case $time >= 1 && $time < 10:
                    $waktu_lisan = 'pagi';
                    break;

                case $time >= 10 && $time < 15:
                    $waktu_lisan = 'siang';
                    break;

                case $time >= 15 && $time < 19:
                    $waktu_lisan = 'sore';
                    break;

                default:
                    $waktu_lisan = 'malam';
                    break;
            }

            $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
            $telegram->sendMessage([
                'chat_id' => $data->telegram_chat_id,
                'parse_mode' => 'html',
                'text' => "Selamat {$waktu_lisan} {$message['chat']['first_name']} {$message['chat']['last_name']},\nSelamat datang di <b>Setos Purchasing Program</b>.\nAnda terdaftar untuk user: <pre>{$user->name}</pre>",
            ]);

            return response()->json([
                'status' => 'success',
                'request' => $request->all()
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                $th->getMessage(),
                $th->getFile(),
                $th->getLine(),
                $request->all()
            ],500);
        }
    }

    public function sendTestMessage()
    {
        return $this->service->sendTestMessage();
    }
}
