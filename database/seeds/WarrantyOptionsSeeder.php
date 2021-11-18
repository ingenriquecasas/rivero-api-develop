<?php

use App\Models\WarrantyOption;
use Illuminate\Database\Seeder;

class WarrantyOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $options = [
            [
                'name' => 'Garantía de fábrica',
                'years' => 3,
                'kms' => 60000,
                'price' => 0,
            ],
            [
                'name' => 'Oro',
                'years' => 4,
                'kms' => 80000,
                'price' => 7280,
            ],
            [
                'name' => 'Platino',
                'years' => 5,
                'kms' => 100000,
                'price' => 14560,
            ],
        ];

        WarrantyOption::truncate();

        foreach ($options as $option) {
            WarrantyOption::create($option);
        }
    }
}
