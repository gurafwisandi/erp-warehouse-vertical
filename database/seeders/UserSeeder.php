<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'name' => 'Purchasing',
                'email' => 'purchasing@gmail.com',
                'roles' => 'Purchasing',
                'password' => bcrypt('12345'),
                'status' => 'Aktif',
            ],
            [
                'name' => 'Sales',
                'email' => 'sales@gmail.com',
                'roles' => 'Sales',
                'status' => 'Aktif',
                'password' => bcrypt('12345'),
            ],
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
