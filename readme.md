# Suitey
Set up the world. Run code before and after PHPUnit.

<p align="center">
<a href="https://travis-ci.org/thecrypticace/suitey"><img src="https://travis-ci.org/thecrypticace/suitey.svg" alt="Build Status"></a>
<a href="https://codecov.io/github/thecrypticace/suitey?branch=master"><img src="https://img.shields.io/codecov/c/github/thecrypticace/suitey/master.svg" alt="Coverage Status"></a>
<a href="https://packagist.org/packages/thecrypticace/suitey"><img src="https://poser.pugx.org/thecrypticace/suitey/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/thecrypticace/suitey"><img src="https://poser.pugx.org/thecrypticace/suitey/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/thecrypticace/suitey"><img src="https://poser.pugx.org/thecrypticace/suitey/license.svg" alt="License"></a>
</p>

## Note
This project is a major WIP. Any feedback, ideas, or issues are appreciated.

## Installation

`composer require thecrypticace/suitey:dev-master --dev`

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

In your `AppServiceProvider` (or, better yet, a dedicated `TestingServiceProvider`) put this in the boot method:

```
Suitey::add([
    new \TheCrypticAce\Suitey\Steps\Migrate,
]);
```

Your migrations will now run _before_ your test runs. Don't forget to remove the `DatabaseMigrations` trait from your tests.

This step is configurable if your have a non-standard setup. You may optionally specify a connection name and/or a path to your migrations.
```
Suitey::add([
    new \TheCrypticAce\Suitey\Steps\Migrate("connection_name", "path_to_migrations"),
]);
```

And if you have databases with multiple migration folders:
```
Suitey::add([
    new \TheCrypticAce\Suitey\Steps\Migrate("foo", "database/migrations/foo"),
    new \TheCrypticAce\Suitey\Steps\Migrate("bar", "database/migrations/bar"),
    new \TheCrypticAce\Suitey\Steps\Migrate("baz", "database/migrations/baz"),
]);
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

## Want to see something meta?

Suitey can run its own tests:
`./tests/Fixture/artisan test`
