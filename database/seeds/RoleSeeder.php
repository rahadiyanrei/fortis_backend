<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = [
            [
                "name"=> "wheel",
                "access" => [
                    "create"=> true,
                    "read"=> true,
                    "update"=> true,
                    "delete"=> true
                ]
            ],
            [
                "name"=> "gallery",
                "access"=> [
                    "create"=> true,
                    "read"=> true,
                    "update"=> true,
                    "delete"=> true
                ]
            ],
            [
                "name"=> "blog",
                "access"=> [
                    "create"=> true,
                    "read"=> true,
                    "update"=> true,
                    "delete"=> true
                ]
            ],
            [
                "name"=> "apparel",
                "access"=> [
                    "create"=> true,
                    "read"=> true,
                    "update"=> true,
                    "delete"=> true
                ]
            ]
        ];
        $data = [
            [
                'name' => 'Super Admin',
                'permissions' => json_encode($permission),
            ],
            [
                'name' => 'All Admin',
                'permissions' => json_encode($permission),
            ],
            [
                'name' => 'Support',
                'permissions' => json_encode($permission),
            ],
        ];
        DB::table('roles')->insert($data);
    }
}
