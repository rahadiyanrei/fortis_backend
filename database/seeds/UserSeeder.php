<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Sentinel;
use Illuminate\Support\Facades\DB;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $credentials = [
            'email'    => 'super.admin@pako.com',
            'password' => 'pako123',
            'fullname' => 'Super Admin'
        ];
        $user = Sentinel::register($credentials);
        DB::table('role_users')->insert([
            'user_id' => 1,
            'role_id' => 1
        ]);
    }
}
