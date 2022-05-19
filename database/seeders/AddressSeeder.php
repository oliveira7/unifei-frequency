<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('adresses')->insert([
            'user_id' => 1,
            'street' => 'Rua teste',
            'district' => 'Bairro teste',
            'city' => 'Cidade teste',
            'cep' => '37500000',
            'number' => 150,
        ]);

        DB::table('adresses')->insert([
            'user_id' => 2,
            'street' => 'Rua teste',
            'district' => 'Bairro teste',
            'city' => 'Cidade teste',
            'cep' => '37500000',
            'number' => 17,
            'complement' => 'Complemento teste'
        ]);
    }
}
