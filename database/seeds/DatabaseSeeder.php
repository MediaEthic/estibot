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
        $this->call('CountriesTableSeeder');
        $this->call('LanguagesTableSeeder');
        $this->call('TimeZonesTableSeeder');
        $this->call('QuotesTableSeeder');
        $this->call('CompaniesTableSeeder');
        $this->call('UsersTableSeeder');
        $this->call('ThirdsTableSeeder');
        $this->call('ContactsTableSeeder');
        $this->call('PrepressesTableSeeder');
        $this->call('PrintingsTableSeeder');
        $this->call('FinishingsTableSeeder');
        $this->call('PackingsTableSeeder');
        $this->call('SubstratesTableSeeder');
        $this->call('LabelsTableSeeder');
        $this->call('StatusesTableSeeder');
        $this->call('SettlementsTableSeeder');
        $this->call('QuotationsTableSeeder');


//        factory(App\Models\Copy::class, 40)->create();
    }
}
