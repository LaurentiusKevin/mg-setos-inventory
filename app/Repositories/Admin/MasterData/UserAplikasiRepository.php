<?php

namespace App\Repositories\Admin\MasterData;

use Illuminate\Support\Facades\DB;

class UserAplikasiRepository
{
    public function users($id = null)
    {
        $data = DB::table('users')
            ->select([
                'users.id',
                'users.role_id',
                'users.department_id',
                'departments.name AS department',
                'roles.name AS role',
                'users.username',
                'users.name',
                'users.email',
                'users.email_verified_at',
                'users.password',
                'users.remember_token',
                'users.created_at',
                'users.updated_at',
            ])
            ->join('roles','users.role_id','=','roles.id')
            ->leftJoin('departments','users.department_id','=','departments.id');

        return $id == null ? $data : $data->where('id','=',$id);
    }
}
