<?php

namespace Database\Seeders;

use App\Models\SysMenu;
use App\Models\SysMenuGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'System Utility',
                'segment_name' => 'system-utility',
                'icon' => 'fas fa-cogs',
                'ord' => 1,
                'is_private' => 1,
                'menu' => [
                    [
                        'name' => 'Menu Group',
                        'segment_name' => 'menu-group',
                        'route' => 'admin/system-utility/menu-group',
                        'ord' => 1,
                        'is_private' => 1,
                    ],
                    [
                        'name' => 'Menu',
                        'segment_name' => 'menu',
                        'route' => 'admin/system-utility/menu',
                        'ord' => 2,
                        'is_private' => 1,
                    ]
                ]
            ]
        ];

        DB::beginTransaction();
        foreach ($data as $group) {
            $groupData = new SysMenuGroup();
            $groupData->name = $group['name'];
            $groupData->segment_name = $group['segment_name'];
            $groupData->icon = $group['icon'];
            $groupData->ord = $group['ord'];
            $groupData->is_private = $group['is_private'];
            $groupData->save();
            foreach ($group['menu'] AS $menu) {
                $menuData = new SysMenu();
                $menuData->sys_menu_group_id = $groupData->id;
                $menuData->name = $menu['name'];
                $menuData->segment_name = $menu['segment_name'];
                $menuData->url = $menu['route'];
                $menuData->ord = $menu['ord'];
                $menuData->is_private = $menu['is_private'];
                $menuData->save();
            }
        }
        DB::commit();
    }
}
