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
            'name' => 'TCS',
            'number_units' =>'8',
            'number_colors' => '4',
            'size_papermaxx' => '254',
            'printable_areax' => '250',
            'plate' => '78',
            'makeready_times' => '0.3',
            'makeready_times_color' => '0.55',
            'unit_cadence' => 'striking',
            'cadence' => '3400',
            'hourly_rate' => '220',
            'overlay_sheet' => '100',
            'overlay_sheet_color' => '100',
            'wastage' => '2',
        ]);

        DB::table('printings')->insert([
            'maker' => 'Heidelberg',
            'name' => 'EM',
            'number_units' =>'8',
            'number_colors' => '4',
            'size_papermaxx' => '254',
            'printable_areax' => '250',
            'plate' => '78',
            'makeready_times' => '0.3',
            'makeready_times_color' => '0.55',
            'unit_cadence' => 'striking',
            'cadence' => '3400',
            'hourly_rate' => '220',
            'overlay_sheet' => '100',
            'overlay_sheet_color' => '100',
            'wastage' => '2',
        ]);

        DB::table('printings')->insert([
            'maker' => 'Heidelberg',
            'name' => 'VIVA',
            'number_units' =>'8',
            'number_colors' => '4',
            'size_papermaxx' => '254',
            'printable_areax' => '250',
            'plate' => '78',
            'makeready_times' => '0.3',
            'makeready_times_color' => '0.55',
            'unit_cadence' => 'striking',
            'cadence' => '3400',
            'hourly_rate' => '220',
            'overlay_sheet' => '100',
            'overlay_sheet_color' => '100',
            'wastage' => '2',
        ]);

        DB::table('printings')->insert([
            'maker' => 'Heidelberg',
            'name' => 'GALAXY',
            'number_units' =>'8',
            'number_colors' => '4',
            'size_papermaxx' => '254',
            'printable_areax' => '250',
            'plate' => '78',
            'makeready_times' => '0.3',
            'makeready_times_color' => '0.55',
            'unit_cadence' => 'striking',
            'cadence' => '3400',
            'hourly_rate' => '220',
            'overlay_sheet' => '100',
            'overlay_sheet_color' => '100',
            'wastage' => '2',
        ]);
    }
}
