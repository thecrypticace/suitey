<?php

return [
    "default" => "default",
    "connections" => [
        "default" => [
            "host"     => env("ARTISAN_DB_HOST", "127.0.0.1"),
            "port"     => env("ARTISAN_DB_PORT", 33060),
            "driver"   => "mysql",
            "database" => "suitey_test_default",
            "prefix"   => "",
            "username" => env("ARTISAN_DB_USER", "homestead"),
            "password" => env("ARTISAN_DB_PASS", "secret"),
        ],

        "foo" => [
            "host"     => env("ARTISAN_DB_HOST", "127.0.0.1"),
            "port"     => env("ARTISAN_DB_PORT", 33060),
            "driver"   => "mysql",
            "database" => "suitey_test_foo",
            "prefix"   => "",
            "username" => env("ARTISAN_DB_USER", "homestead"),
            "password" => env("ARTISAN_DB_PASS", "secret"),
        ],

        "bar" => [
            "host"     => env("ARTISAN_DB_HOST", "127.0.0.1"),
            "port"     => env("ARTISAN_DB_PORT", 33060),
            "driver"   => "mysql",
            "database" => "suitey_test_bar",
            "prefix"   => "",
            "username" => env("ARTISAN_DB_USER", "homestead"),
            "password" => env("ARTISAN_DB_PASS", "secret"),
        ],
    ],
];
