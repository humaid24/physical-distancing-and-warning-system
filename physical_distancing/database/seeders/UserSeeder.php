<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, Hash};

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Super Admin', 
            'email' => 'super.admin@gmail.com', 
            'username' => 'admin',
            'password' => Hash::make('adminadmin'),
            'isSupperAdmin' => 'Y',
            'isApprovedByAdmin' => 'Y'
        ]);
    }
}
