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
            'profession' =>'Développeuse web',
            'email' => 'emilie.rozis@erp-ethic.com',
            'phone' => '+33 (0)6 45 88 11 08',
            'password' => app('hash')->make('MediaEthic64Estibot'),
            'remember_token' => Str::random(10),
        ]);

        DB::table('users')->insert([
            'name' => 'Gilles',
            'surname' =>'BONIN',
            'profession' =>'Président',
            'email' => 'gilles.bonin@erp-ethic.com',
            'phone' => '+33 (0)6 09 43 87 12',
            'password' => app('hash')->make('lompks'),
            'remember_token' => Str::random(10),
        ]);

        DB::table('users')->insert([
            'name' => 'Charles',
            'surname' =>'COUSSY',
            'profession' =>'Ingénieur d\'affaires',
            'email' => 'charles.coussy@erp-ethic.com',
            'phone' => '+33 (0)6 88 16 22 43',
            'password' => app('hash')->make('coussy'),
            'remember_token' => Str::random(10),
        ]);

        factory(App\Models\User::class, 14)->create();
    }
}
