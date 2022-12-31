<?php

namespace Database\Seeders;
use Modules\FAQ\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Get Started',

            ],
            ['name' => 'Login & Access',

            ],
            ['name' => 'Billing & Payment',

            ],

            ['name' => 'My Benefits',

            ],

            ['name' => 'Account Settings',

            ],
        ];

        Category::insert($categories);
    }
}
