<?php

use Illuminate\Database\Seeder;

class SubstratesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Substrate::class, 30)->create();
    }
}
