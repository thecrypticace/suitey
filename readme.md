# Suitey
Set up the world. Run code before and after PHPUnit.

<p align="center">
<a href="https://travis-ci.org/thecrypticace/suitey"><img src="https://travis-ci.org/thecrypticace/suitey.svg" alt="Build Status"></a>
<a href="https://codecov.io/github/thecrypticace/suitey?branch=master"><img src="https://img.shields.io/codecov/c/github/thecrypticace/suitey/master.svg" alt="Coverage Status"></a>
<a href="https://packagist.org/packages/thecrypticace/suitey"><img src="https://poser.pugx.org/thecrypticace/suitey/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/thecrypticace/suitey"><img src="https://poser.pugx.org/thecrypticace/suitey/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/thecrypticace/suitey"><img src="https://poser.pugx.org/thecrypticace/suitey/license.svg" alt="License"></a>
</p>

## tl;dr (Laravel 5.5)
1. `composer require thecrypticace/suitey`
2. Add `TheCrypticAce\Suitey\Laravel\SuiteyServiceProvider::class` to your `config/app.php` file. (Not necessary in Laravel 5.5+)
2. `php artisan vendor:publish --tag=suitey`
3. Update `steps` list to configure and run the steps you want before your tests.

## Installation

`composer require thecrypticace/suitey`

If you're running Laravel 5.4 then add this to the providers in your config/app.php file:

`TheCrypticAce\Suitey\Laravel\SuiteyServiceProvider::class`

This is not required when using Laravel 5.5.

## Usage

Run your tests with the `test` artisan command:
```
php artisan test
```

This also accepts any parameter that PHPUnit does:
```
php artisan test --filter=my_test_method_name
```

Want to pass arguments to `artisan` before PHPUnit? Use a `--` to separate the two lists:
```
php artisan test -vvv -- --filter=my_test_method_name
```

## Adding steps

When you run `php artisan test` you'll be running one step: PHPUnit. You'll can see this because you will get
output that looks like this:
```
[1/1] Run PHPUnit
… test details here …
```

Lets fix that.

### Publishing the config
Run `php artisan vendor:publish --tag=suitey` to publish the config file. This file is where you can detail what steps run and how to load the test environment variables for tests.

### Adding steps
In the config for Suitey you will see a `steps` array that looks like this:
```php
"steps" => [
    // \TheCrypticAce\Suitey\Migrate::class,
    // \TheCrypticAce\Suitey\RefreshDatabase::class,
    // [
    //     "class" => \TheCrypticAce\Suitey\SeedDatabase::class,
    //     "options" => ["class" => "ExampleSeeder"],
    // ]
],
```

Uncomment the `Migrate` step and your database migrations will run before your tests.

```php
"steps" => [
    \TheCrypticAce\Suitey\Migrate::class,

    // \TheCrypticAce\Suitey\RefreshDatabase::class,
    // [
    //     "class" => \TheCrypticAce\Suitey\SeedDatabase::class,
    //     "options" => ["class" => "ExampleSeeder"],
    // ]
],
```

*Note: You may resolve the class through the container instead of using the facade if your wish.*

Your migrations will now run _before_ your test runs. Don't forget to remove the `DatabaseMigrations` trait from your tests.

This step is configurable if your have an atypical setup. You may optionally specify a connection name and/or a path to your migrations.
```php
"steps" => [
    [
        "class" => \TheCrypticAce\Suitey\Migrate::class,
        "options" => ["database" => "connection_name", "path" => "path_to_migrations"],
    ],
],
```

And if you have more than one migration folder:
```php
"steps" => [
    [
        "class" => \TheCrypticAce\Suitey\Migrate::class,
        "options" => ["database" => "foo", "path" => "database/migrations/foo"],
    ],
    [
        "class" => \TheCrypticAce\Suitey\Migrate::class,
        "options" => ["database" => "bar", "path" => "database/migrations/bar"],
    ],
    [
        "class" => \TheCrypticAce\Suitey\Migrate::class,
        "options" => ["database" => "baz", "path" => "database/migrations/baz"],
    ],
],
```

## Available Steps

| Class | Config Option | Description |
| ------|---------------|-------------|
| `Migrate` | | Migrate a database via `migrate` and `migrate:rollback` before/after phpunit |
|  | database | *optional* The default connection to use during migration |
|  | path | *optional*The path to your migration files |
| `RefreshDatabase` | | Refresh a database via `migrate:refresh` before starting phpunit |
|  | database | *optional*The default connection to use during migration |
|  | path | *optional*The path to your migration files |
| `SeedDatabase` | | Run the given seeder before starting PHPUnit |
|  | name | *required* The name of the seeder you would like to run |
| `RunCode` | | Run a closure! |
|  | name | *required* The name displayed to the user. This can be a closure that determines the name if needed. |
|  | code | *required* The code to run |

## Want to see something meta?

Suitey can run its own tests:
`./tests/Fixture/artisan test`
