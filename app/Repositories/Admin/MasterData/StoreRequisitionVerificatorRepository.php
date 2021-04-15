<?php

namespace App\Repositories\Admin\MasterData;

use Illuminate\Support\Facades\DB;

class StoreRequisitionVerificatorRepository
{
    public function department($id = null)
    {
        $data = DB::table('store_requisition_verificators')
            ->select([
                'store_requisition_verificators.id',
                'users.username',
                'users.role_id',
                'users.name',
                'users.email',
                'users.department_id',
                'departments.name AS department',
                'users.remember_token',
            ])
            ->leftJoin('users','store_requisition_verificators.user_id','=','users.id')
            ->leftJoin('departments','users.department_id','=','departments.id');

        return ($id !== null) ? $data->where('id','=',$id) : $data;
    }

    public function checkCode($code)
    {
        return DB::table('departments')
            ->where('code','=',$code)
            ->get()->count();
    }

    public function listUser()
    {
        return DB::table('users')
            ->select([
                'users.id',
                'users.username',
                'users.role_id',
                'users.name',
                'users.email',
                'users.department_id',
                'departments.name AS department',
                'users.remember_token',
            ])
            ->whereNotIn('users.id',function ($query) {
                $query->select('user_id')->from('store_requisition_verificators');
            })
            ->leftJoin('departments','users.department_id','=','departments.id');
    }
}
