<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Émilie',
            'surname' =>'ROZIS',
            'email' => 'emilie.rozis@erp-ethic.com',
            'password' => app('hash')->make('MediaEthic64Estibot'),
            'remember_token' => Str::random(10),
        ]);

        factory(App\Models\User::class, 14)->create();
    }
}
