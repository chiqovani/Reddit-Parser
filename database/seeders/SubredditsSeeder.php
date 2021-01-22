<?php

namespace Database\Seeders;

use App\Models\SubredditsModel;
use Illuminate\Database\Seeder;

class SubredditsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubredditsModel::insert([
            ['subbredit'=>'AskReddit',
            'procesing'=>0,
            ],
            ['subbredit'=>'LifeProTips',
                'procesing'=>0,
            ],
        ]);
    }
}
