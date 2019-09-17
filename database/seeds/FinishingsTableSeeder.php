<?php

use Illuminate\Database\Seeder;

class FinishingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('finishings')->insert([
            'printing_id' => '1',
            'name' => 'Vernis',
            'makeready_times' => '0.15',
            'cadence' => '3400',
            'overlay_sheet' => '0',
        ]);

        DB::table('finishings')->insert([
            'printing_id' => '2',
            'name' => 'Vernis avec réserve',
            'makeready_times' => '0.15',
            'cadence' => '3400',
            'overlay_sheet' => '0',
        ]);

        DB::table('finishings')->insert([
            'printing_id' => '3',
            'name' => 'Dorure à chaud',
            'consumable' => true,
            'makeready_times' => '0.3',
            'cadence' => '2833',
            'overlay_sheet' => '75',
        ]);

        DB::table('finishings')->insert([
            'printing_id' => '4',
            'name' => 'Dorure à froid',
            'consumable' => true,
            'makeready_times' => '0.3',
            'cadence' => '2833',
            'overlay_sheet' => '75',
        ]);

        DB::table('finishings')->insert([
            'printing_id' => '4',
            'name' => 'Gaufrage',
            'makeready_times' => '0.4',
            'cadence' => '3400',
            'overlay_sheet' => '50',
        ]);

        DB::table('finishings')->insert([
            'printing_id' => '4',
            'name' => 'Pelliculage',
            'consumable' => true,
            'makeready_times' => '0.25',
            'cadence' => '3400',
            'overlay_sheet' => '20',
        ]);
    }
}
