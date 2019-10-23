<?php

use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
            'name' => 'Devis créé',
        ]);

        DB::table('statuses')->insert([
            'name' => 'Devis transmis',
        ]);

        DB::table('statuses')->insert([
            'name' => 'Devis relancé',
        ]);

        DB::table('statuses')->insert([
            'name' => 'Devis transformé',
        ]);

        DB::table('statuses')->insert([
            'name' => 'Devis archivé',
        ]);
    }
}
