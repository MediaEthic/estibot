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
            'name' => 'Vernis',
        ]);

        DB::table('finishings')->insert([
            'name' => 'Vernis avec réserve',
        ]);

        DB::table('finishings')->insert([
            'name' => 'Dorure à chaud',
            'consumable' => true,
        ]);

        DB::table('finishings')->insert([
            'name' => 'Dorure à froid',
            'consumable' => true,
        ]);

        DB::table('finishings')->insert([
            'name' => 'Gaufrage',
        ]);

        DB::table('finishings')->insert([
            'name' => 'Pelliculage',
            'consumable' => true,
        ]);
    }
}
