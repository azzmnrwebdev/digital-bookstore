<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id' => 1,
            'fullname' => 'Administrator',
            'username' => 'administrator',
            'email' => 'admin@gmail.com',
            'gender' => 'L',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
            'is_active' => 1,
            'status' => 'approved',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time())
        ]);

        Profile::create([
            'id' => 1,
            'users_id' => 1,
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time())
        ]);
    }
}
