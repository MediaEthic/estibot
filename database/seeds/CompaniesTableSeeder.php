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
            'name' => 'LP2i étiquettes',
            'logo' => 'lp2i-logo.jfif',
            'phone' => '+33 (0)4 92 08 84 24',
            'email' => 'lp2i@wanadoo.fr',
            'address_line2' => 'Le Broc Center - BP 336',
            'address_line3' => '1ère Avenue 5600 mètres',
            'zipcode' => '06514',
            'city' => 'Carros CEDEX',
            'legal_form' => 'SASU ',
            'capital' => '65000',
            'register' => 'Grasse B 352 119 200',
            'siret' => '352 119 200 00033',
            'tva' => 'FR64 352119200',
            'head_quotation' => 'Nous vous remercions de votre demande de prix et vous prions de bien vouloir trouver ci-après nos meilleures conditions pour la réalisation de :',
            'foot_quotation' => 'Nous espérons que cette proposition retiendra favorablement votre attention et vous prions de croîre en l\'assurance de nos courtoises salutations.',
            'facebook' => 'https://www.facebook.com/lp2ietiquettes',
            'linkedin' => 'https://fr.linkedin.com/company/lp2i-etiquettes',
            'prepress' => 'PAO',
            'winder' => 'BOB',
            'api_url' => 'http://89.92.37.229',
        ]);
    }
}
