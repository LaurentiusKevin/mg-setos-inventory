<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\MasterData\UserAplikasiService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserAplikasiController extends Controller
{
    private $service;

    public function __construct(UserAplikasiService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.master-data.user-aplikasi.index');
    }

    public function data()
    {
        try {
            return DataTables::of(
                $this->service->data()
            )->addColumn('action', function ($data) {
                return view('admin.master-data.user-aplikasi.action');
            })->make(true);
        } catch (\Exception $ex) {
            dd($ex);
        }
    }

    public function indexCreate()
    {
        return view('admin.master-data.user-aplikasi.create',$this->service->indexCreateData());
    }

    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required',
            'name' => 'required',
            'username' => 'required',
        ]);

        $role = $request->get('role');
        $department = $request->get('department') ?? null;
        $name = $request->get('name');
        $email = $request->get('email');
        $username = $request->get('username');
        $password = $request->get('password');
        $id = $request->get('id');

        return $this->service->storeData($role,$department,$name,$email,$username,$password,$id);
    }

    public function indexEdit($id)
    {
        return view('admin.master-data.user-aplikasi.edit',$this->service->indexEditData($id));
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $id = $request->get('id');

        return $this->service->deleteData($id);
    }
}
