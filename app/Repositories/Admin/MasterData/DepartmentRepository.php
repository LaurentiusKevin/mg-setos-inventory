<?php

namespace App\Repositories\Admin\MasterData;

use Illuminate\Support\Facades\DB;

class DepartmentRepository
{
    public function department($id = null)
    {
        $data = DB::table('departments');

        return ($id !== null) ? $data->where('id','=',$id) : $data->whereNull('deleted_at');
    }

    public function checkCode($code)
    {
        return DB::table('departments')
            ->where('code','=',$code)
            ->whereNull('deleted_at')
            ->get()->count();
    }
}
