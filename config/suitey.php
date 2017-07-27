<?php

return [
    "steps" => [
        // \TheCrypticAce\Suitey\MigrateDatabase::class,
        // \TheCrypticAce\Suitey\RefreshDatabase::class,
        // [
        //     "class" => \TheCrypticAce\Suitey\SeedDatabase::class,
        //     "options" => ["class" => "ExampleSeeder"],
        // ]
    ],

    "environments" => [
        [
            "path" => base_path(".env.testing"),
            "loader" => \TheCrypticAce\Suitey\Environment\DotEnv::class,
        ],
        [
            "path" => base_path("phpunit.xml.dist"),
            "loader" => \TheCrypticAce\Suitey\Environment\PHPUnit::class,
        ],
        [
            "path" => base_path("phpunit.xml"),
            "loader" => \TheCrypticAce\Suitey\Environment\PHPUnit::class,
        ],
    ]
];
