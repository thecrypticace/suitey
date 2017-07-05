<?php

return [
    "default" => "default",
    "connections" => [
        "default" => [
            "host"     => env("ARTISAN_DB_HOST"),
            "port"     => env("ARTISAN_DB_PORT"),
            "driver"   => "mysql",
            "database" => "suitey_test_default",
            "prefix"   => "",
            "username" => env("ARTISAN_DB_USER"),
            "password" => env("ARTISAN_DB_PASS"),
        ],

        "foo" => [
            "host"     => env("ARTISAN_DB_HOST"),
            "port"     => env("ARTISAN_DB_PORT"),
            "driver"   => "mysql",
            "database" => "suitey_test_foo",
            "prefix"   => "",
            "username" => env("ARTISAN_DB_USER"),
            "password" => env("ARTISAN_DB_PASS"),
        ],

        "bar" => [
            "host"     => env("ARTISAN_DB_HOST"),
            "port"     => env("ARTISAN_DB_PORT"),
            "driver"   => "mysql",
            "database" => "suitey_test_bar",
            "prefix"   => "",
            "username" => env("ARTISAN_DB_USER"),
            "password" => env("ARTISAN_DB_PASS"),
        ],
    ],
];
