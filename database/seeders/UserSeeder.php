<?php

namespace Database\Seeders;

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
        DB::table('users')->insert([
            'name' => 'Yuri Oliveira',
            'email' => 'yuri@gmail.com',
            'telephone' => '553599303030',
            'registration_code' => '2016000222',
            'roleable_id' => '1',
            'roleable_type' => 'App\Models\Student',
            'password' => Hash::make('password'),
        ]);

        DB::table('users')->insert([
            'name' => 'Lina',
            'email' => 'lina@gmail.com',
            'telephone' => '553599202020',
            'registration_code' => '2018000232',
            'roleable_id' => '1',
            'roleable_type' => 'App\Models\Teacher',
            'password' => Hash::make('password'),
        ]);
    }
}
