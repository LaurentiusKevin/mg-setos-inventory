<?php

namespace App\Services\Admin\MasterData;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Admin\MasterData\UserAplikasiRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserAplikasiService
{
    private $repository;

    public function __construct(UserAplikasiRepository $repository)
    {
        $this->repository = $repository;
    }

    public function indexCreateData()
    {
        return [
            'role' => Role::all(),
            'department' => Department::all()
        ];
    }

    public function data()
    {
        return $this->repository->users();
    }

    public function storeData($role,$department,$name,$email,$username,$password,$id = null)
    {
        try {
            $checkUser = DB::table('users')
                ->where([
                    ['username','=',$username],
                    ['deleted_at','=',null],
                ]);
            if ($id !== null) $checkUser->where('id','<>',$id);

            if ($checkUser->exists()) {
                return response()->json(['status' => 'failed', 'message' => 'Username sudah terdaftar']);
            } else {
                DB::beginTransaction();

                if ($id == null) {
                    $data = new User();
                    $data->password = Hash::make($password);
                } else {
                    $data = User::find($id);
                    if ($password !== null) $data->password = Hash::make($password);
                }
                $data->role_id = $role;
                $data->department_id = $department;
                $data->name = $name;
                $data->email = $email;
                $data->username = $username;
                $data->save();

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'redirect' => route('admin.master-data.user-aplikasi.view.index')
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ]);
        }
    }

    public function indexEditData($id)
    {
        return [
            'role' => Role::all(),
            'data' => User::find($id),
            'department' => Department::all()
        ];
    }

    public function deleteData($id)
    {
        try {
            DB::beginTransaction();
            User::find($id)->delete();
            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ]);
        }
    }
}
