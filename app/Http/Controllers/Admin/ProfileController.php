<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Notifications\StoreRequisitionCreated;
use App\Services\Admin\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

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
}
