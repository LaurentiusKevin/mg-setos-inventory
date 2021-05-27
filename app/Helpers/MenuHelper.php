<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MenuHelper
{
    public static function getRole()
    {
        $prefix = ltrim(request()->route()->getPrefix(),'/');
        $role_id = Auth::user()->role_id;

        if ($role_id == null) {
            $data = new \stdClass();
            $data->view = 1;
            $data->create = 1;
            $data->edit = 1;
            $data->delete = 1;
            return $data;
        } else {
            return DB::table('role_menus')
                ->join('sys_menus','role_menus.sys_menus_id','=','sys_menus.id')
                ->select([
                    'sys_menus.name',
                    'role_menus.view',
                    'role_menus.create',
                    'role_menus.edit',
                    'role_menus.delete',
                ])
                ->where([
                    ['sys_menus.url','=',$prefix],
                    ['role_menus.roles_id','=',$role_id]
                ])
                ->first();
        }
    }
}
