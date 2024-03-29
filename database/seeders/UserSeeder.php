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
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'roles' => 'Admin',
                'password' => bcrypt('12345'),
                'status' => 'Aktif',
            ],
            [
                'name' => 'Produksi',
                'email' => 'produksi@gmail.com',
                'roles' => 'Produksi',
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
            [
                'name' => 'Gudang',
                'email' => 'gudang@gmail.com',
                'roles' => 'Gudang',
                'status' => 'Aktif',
                'password' => bcrypt('12345'),
            ],
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
