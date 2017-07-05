<?php

return [
    "environments" => [
        [
            "path" => __DIR__."/../.env.testing",
            "loader" => \TheCrypticAce\Suitey\Environment\DotEnv::class,
        ],
        [
            "path" => __DIR__."/../phpunit.xml.dist",
            "loader" => \TheCrypticAce\Suitey\Environment\PHPUnit::class,
        ],
        [
            "path" => __DIR__."/../phpunit.xml",
            "loader" => \TheCrypticAce\Suitey\Environment\PHPUnit::class,
        ],
    ]
];
