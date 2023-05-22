<?php

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
        DB::table('user')->insert([
            'username' => 'admin',
            'email' => 'lynv110@gmail.com',
            'user_group_id' => '1',
            'password' => Hash::make('123456'),
            'full_name' => 'Ly Nguyen Van',
            'status' => 1,
        ]);
    }
}
