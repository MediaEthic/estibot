<?php

use Illuminate\Database\Seeder;

class ThirdsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Third::class, 15)->create();
    }
}
