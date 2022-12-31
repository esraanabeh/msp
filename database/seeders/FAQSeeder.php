<?php

namespace Database\Seeders;
use Modules\FAQ\Models\FAQ;
use Illuminate\Database\Seeder;

class FAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $FAQ = [
            ['Category_id' => 1,
            'question' =>'How to upgrade to account',
            'answer'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry.
             Lorem Ipsum has been the industry'
            ],
            ['Category_id' => 1,
            'question' =>'Iforget My Password',
            'answer'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry.
             Lorem Ipsum has been the industry'

            ],
            ['Category_id' => 2,
            'question' =>'Ican not reset My Password',
            'answer'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry.
             Lorem Ipsum has been the industry'

            ],

            ['Category_id' =>3,
            'question' =>'How to upgrade to Pro account',
            'answer'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry.
             Lorem Ipsum has been the industry'

            ],

            ['Category_id' => 4,
            'question' =>'How to upgrade to account',
            'answer'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry.
             Lorem Ipsum has been the industry'

            ],
            ['Category_id' => 1,
            'question' =>'How to change Password',
            'answer'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry.
             Lorem Ipsum has been the industry'

            ],
            ['Category_id' => 1,
            'question' =>'How to upgrade to account',
            'answer'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry.
             Lorem Ipsum has been the industry'

            ],
            ['Category_id' => 2,
            'question' =>'How to upgrade to account',
            'answer'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry.
             Lorem Ipsum has been the industry'

             ],
        ];

        FAQ::insert($FAQ);
    }
}
