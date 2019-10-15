<?php

use Illuminate\Database\Seeder;

class SettlementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settlements')->insert([
            'name' => 'Règlement à réception',
            'payment_within' => '0',
            'payment_on' => '0',
        ]);

        DB::table('settlements')->insert([
            'name' => 'Règlement à 15 jours nets',
            'payment_within' => '15',
            'payment_on' => '0',
        ]);

        DB::table('settlements')->insert([
            'name' => 'Règlement à 30 jours nets',
            'payment_within' => '30',
            'payment_on' => '0',
        ]);

        DB::table('settlements')->insert([
            'name' => 'Règlement à 45 jours nets',
            'payment_within' => '45',
            'payment_on' => '0',
        ]);

        DB::table('settlements')->insert([
            'name' => 'Règlement à 60 jours nets',
            'payment_within' => '60',
            'payment_on' => '0',
        ]);

        DB::table('settlements')->insert([
            'name' => 'Règlement à 90 jours nets',
            'payment_within' => '90',
            'payment_on' => '0',
        ]);

        DB::table('settlements')->insert([
            'name' => 'Règlement à 30 jours FDM',
            'payment_within' => '30',
            'payment_on' => '99',
        ]);

        DB::table('settlements')->insert([
            'name' => 'Règlement à 45 jours FDM',
            'payment_within' => '45',
            'payment_on' => '99',
        ]);

        DB::table('settlements')->insert([
            'name' => 'Règlement à 60 jours FDM',
            'payment_within' => '60',
            'payment_on' => '99',
        ]);
    }
}
