<?php

use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'name' => 'Ethic SAS',
            'logo' => 'logo-ethic-software.png',
            'phone' => '+33 (0)5 59 20 81 31',
            'email' => 'info@ethicsoftware.com',
            'address_line3' => '2 place du Réduit',
            'zipcode' => '64100',
            'city' => 'Bayonne',
            'legal_form' => 'SAS',
            'capital' => '40000',
            'register' => 'Bayonne',
            'siret' => '429 542 764 00031',
            'tva' => 'FR85 429542764',
            'head_quotation' => 'Nous vous remercions de votre demande de prix et vous prions de bien vouloir trouver ci-après nos meilleures conditions pour la réalisation de :',
            'foot_quotation' => 'Nous espérons que cette proposition retiendra favorablement votre attention et vous prions de croîre en l\'assurance de nos courtoises salutations.',
            'winder' => 'BOB',
        ]);
    }
}
