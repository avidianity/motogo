<?php

return [
    'rider' => [
        'storage' => [
            'driver' => env('RIDER_REGISTRATION_STORAGE_DRIVER', 'local'),
            'root' => storage_path('app/rider'),
            'serve' => true,
            'throw' => true,
        ],
    ],
];
