<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class Roles extends Seeder
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
                'name' => 'Super Admin',
                'permissions' => '[
                    {
                        "module": "wheel",
                        "access": {
                            "create": true,
                            "read": true,
                            "update": true,
                            "delete": true
                        }
                    },
                    {
                        "module": "gallery",
                        "access": {
                            "create": true,
                            "read": true,
                            "update": true,
                            "delete": true
                        }
                    },
                    {
                        "module": "blog",
                        "access": {
                            "create": true,
                            "read": true,
                            "update": true,
                            "delete": true
                        }
                    },
                    {
                        "module": "apparel",
                        "access": {
                            "create": true,
                            "read": true,
                            "update": true,
                            "delete": true
                        }
                    }
                ]',
            ],
        ];
        DB::table('roles')->insert($data);
    }
}
