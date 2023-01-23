<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Customer',
            'email' => 'customer@gmail.com',
            'mobile' => '8086261316',
            'user_type' => '0',
            'password' => Hash::make('123456789'),
        ]);
    }
}
