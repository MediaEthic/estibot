<?php

use Illuminate\Database\Seeder;

class PackingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packings')->insert([
            'name' => 'Bobineuse',
            'makeready_times' => '0.6',
            'unit_cadence' => 'linear',
            'cadence' => '20',
            'duration' => '0.108',
            'hourly_rate' => '50',
        ]);
    }
}
