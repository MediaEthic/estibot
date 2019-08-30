<?php

use Illuminate\Database\Seeder;

class PrintingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('printings')->insert([
            'maker' => 'Heidelberg',
            'name' => 'Gallus TCS 250 C',
            'number_units' =>'12',
            'number_colors' => '4',
            'size_paperminy' => '101.6',
            'size_papermaxx' => '254',
            'printable_areax' => '254',
            'weight_minimum' => '100',
            'weight_maximum' => '180',
            'makeready_times' => '1.5',
            'cadence' => '30000',
            'hourly_rate' => '800',
            'overlay_sheet' => '5000',
            'wastage' => '2',
        ]);
    }
}
