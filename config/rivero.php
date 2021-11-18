<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rivero services and financial parameters
    |--------------------------------------------------------------------------
    */

    'warranty' => [
        'options' => [
            [
                'name' => 'Garantía de fábrica',
                'years' => 3,
                'mileageinkm' => 60000,
                'price' => 0,
            ],
            [
                'name' => 'Oro',
                'years' => 4,
                'mileageinkm' => 80000,
                'price' => 7280,
            ],
            [
                'name' => 'Platino',
                'years' => 5,
                'mileageinkm' => 100000,
                'price' => 14560,
            ],
        ],
        'interests' => [
            12 => 15.54,
            60 => 16.54,
        ],
    ],
];
