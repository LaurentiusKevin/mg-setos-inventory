<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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
                'name' => 'Developer',
                'username' => 'superadmin',
                'password' => Hash::make('GriffinsOnLand')
            ],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('1111')
            ]
        ];
        $user = new User();
        DB::beginTransaction();
        foreach ($data AS $item) {
            $user->name = $item['name'];
            $user->username = $item['username'];
            $user->password = $item['password'];
            $user->save();
        }
        DB::commit();
    }
}
