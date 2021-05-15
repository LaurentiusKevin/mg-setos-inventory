<?php

namespace App\Repositories\Admin;

use App\Models\User;

class ProfileRepository
{
    public function userInfo($user_id)
    {
        return User::query()
            ->select([
                'users.role_id',
                'users.department_id',
                'users.username',
                'users.name',
                'roles.name AS role_name',
                'departments.name AS department_name',
                'users.email',
                'users.email_verified_at',
                'users.created_at',
                'users.updated_at',
            ])
            ->leftJoin('roles','users.role_id','=','roles.id')
            ->leftJoin('departments','users.department_id','=','departments.id')
            ->where('users.id','=',$user_id)
            ->first();
    }
}
