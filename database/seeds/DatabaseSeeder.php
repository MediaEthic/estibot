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
//        $this->call('UsersTableSeeder');
        $this->call('SettlementsTableSeeder');
//        $this->call('ThirdsTableSeeder');
//        $this->call('ContactsTableSeeder');
//        $this->call('SubstratesTableSeeder');
//        $this->call('LabelsTableSeeder');
//        $this->call('StatusesTableSeeder');
//        $this->call('QuotationsTableSeeder');
    }
}
