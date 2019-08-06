<?php

use Illuminate\Database\Seeder;

class QuotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quotes')->insert([
            'quote' => 'Les trois grands éléments de la civilisation moderne sont la poudre, l’imprimerie et la religion protestante.',
            'author' =>'Thomas Carlyle',
        ]);

        DB::table('quotes')->insert([
            'quote' => 'L’imprimerie est à l’écriture ce que l’écriture avait été aux hiéroglyphes : elle a fait faire un second pas à la pensée.',
            'author' =>'Rivarol',
        ]);

        DB::table('quotes')->insert([
            'quote' => 'L’imprimerie est l’artillerie de la pensée.',
            'author' =>'Rivarol',
        ]);

        DB::table('quotes')->insert([
            'quote' => 'L’invention de l’imprimerie est le plus grand événement de l’histoire. C’est la révolution mère. C’est le mode d’expression de l’humanité qui se renouvelle totalement.',
            'author' =>'Victor Hugo',
        ]);

        DB::table('quotes')->insert([
            'quote' => 'Si un autre Messie naissait, il pourrait difficilement faire autant de bien que les imprimeries.',
            'author' =>'Georg Christoph Lichtenberg ',
        ]);

        DB::table('quotes')->insert([
            'quote' => 'Les seuls papiers qui m’intéressent, ce sont ceux de l’imprimerie nationale, avec la tronche de Blaise dans le coin.',
            'author' =>'Michel Audiard',
        ]);

        DB::table('quotes')->insert([
            'quote' => 'Ici repose Philippe Chastrusse - Imprimeur - La Composition fut toute sa vie - et bien que son caractère incorrigible - le mit parfois à rude épreuve - il fit toujours bonne impression.',
            'author' =>'Epitaphe de Philippe Chastrusse',
        ]);

        DB::table('quotes')->insert([
            'quote' => 'J’ai remarqué que le jugement le plus dépourvu de fondement, la plus sotte grossièreté prend du poids, du fait de l’influence magique de l’imprimerie.',
            'author' =>'Alexandre Pouchkine',
        ]);

        DB::table('quotes')->insert([
            'quote' => 'J’ai entendu dire dans ma jeunesse que l’odeur du pain chaud est insupportable aux malades mais je répète que les fleurs sentent l’encre d’imprimerie.',
            'author' =>'André Breton',
        ]);

        DB::table('quotes')->insert([
            'quote' => 'Ce qui a répandu le plus de lumière dans le monde, c’est une couleur noire : l’encre d’imprimerie.',
            'author' =>'Paul Masson',
        ]);
    }
}
