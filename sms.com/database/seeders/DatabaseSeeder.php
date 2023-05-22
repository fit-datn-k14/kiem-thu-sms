<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('user')->insert([
            'username' => 'admin',
            'email' => 'lynv110@gmail.com',
            'user_group_id' => '1',
            'password' => Hash::make('123456'),
            'full_name' => 'Ly Nguyen Van',
            'status' => 1,
            'is_sadmin' => 1,
        ]);
    }
}
