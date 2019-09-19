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
        $this->call('PrepressesTableSeeder');
        $this->call('PrintingsTableSeeder');
        $this->call('FinishingsTableSeeder');
        $this->call('PackingsTableSeeder');
        $this->call('SubstratesTableSeeder');
        $this->call('LabelsTableSeeder');
        $this->call('QuotationsTableSeeder');


//        factory(App\Models\Copy::class, 40)->create();
    }
}
