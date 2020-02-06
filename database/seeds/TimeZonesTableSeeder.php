<?php

use Illuminate\Database\Seeder;
use App\Models\TimeZone;

class TimeZonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Empty the table
        TimeZone::truncate();
        // Get all from the JSON file
        $JSON_timezones = TimeZone::allJSON();
        foreach ($JSON_timezones as $timezone) {
            TimeZone::create([
                'name'      => ((isset($timezone['name'])) ? $timezone['name'] : null),
                'abbr'      => ((isset($timezone['abbr'])) ? $timezone['abbr'] : null),
                'offset'    => ((isset($timezone['offset'])) ? $timezone['offset'] : null),
                'isdst'     => ((isset($timezone['isdst'])) ? $timezone['isdst'] : null),
                'text'      => ((isset($timezone['text'])) ? $timezone['text'] : null),
                'utc'       => ((isset($timezone['utc'])) ? $timezone['utc'] : null),
            ]);
        }
    }
}
