<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call('QuotesTableSeeder');
         $this->call('UsersTableSeeder');
         $this->call('CountriesTableSeeder');
         $this->call('ThirdsTableSeeder');
         $this->call('ContactsTableSeeder');

        factory(App\Models\Substrate::class, 30)->create();
        factory(App\Models\Label::class, 30)->create();
        factory(App\Models\Copy::class, 40)->create();
        factory(App\Models\Quotation::class, 50)->create();
    }
}
