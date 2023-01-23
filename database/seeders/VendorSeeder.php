<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Vendor',
            'email' => 'vendor@gmail.com',
            'mobile' => '9847728171',
            'user_type' => '2',
            'password' => Hash::make('123456789'),
        ]);
    }
}
