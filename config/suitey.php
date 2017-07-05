<?php

return [
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
