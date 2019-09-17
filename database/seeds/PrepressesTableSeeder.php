<?php

use Illuminate\Database\Seeder;

class PrepressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prepresses')->insert([
            'name' => 'Prépresse',
            'hourly_rate' => '40',
        ]);
    }
}
