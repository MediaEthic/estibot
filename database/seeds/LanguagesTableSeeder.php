<?php

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Empty the table
        Language::truncate();
        // Get all from the JSON file
        $JSON_languages = Language::allJSON();
        foreach ($JSON_languages as $language) {
            Language::create([
                'alpha3'    => ((isset($language['alpha3'])) ? $language['alpha3'] : null),
                'alpha2'    => ((isset($language['alpha2'])) ? $language['alpha2'] : null),
                'english'   => ((isset($language['english'])) ? $language['english'] : null),
            ]);
        }
    }
}
