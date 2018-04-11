# A Laravel ratelimiter that supports ETag

[![Latest Version on Packagist](https://img.shields.io/packagist/v/m1guelpf/laravel-etag.svg?style=flat-square)](https://packagist.org/packages/m1guelpf/laravel-etag)
[![Total Downloads](https://img.shields.io/packagist/dt/m1guelpf/laravel-etag.svg?style=flat-square)](https://packagist.org/packages/m1guelpf/laravel-etag)

## Installation

You can install the package via composer:

```bash
composer require m1guelpf/laravel-etag
```

To use it, you must replace the throttle middleware in your Http Kernel file with `\M1guelpf\Etag\EtagMiddleware::class`:

```php
// app/Http/Kernel.php

...
protected $routeMiddleware = [
        ...
        'throttle' => \M1guelpf\Etag\EtagMiddleware::class,
    ];
```

## Usage

To throttle a route while respecting ETag headers, just add the throttle middleware like you'd do without the package:

```php
// in a routes file
Route::get('my-page', 'MyController')->middleware('throttle');
```

All the options available for the stock Laravel throttle are also applicable with this package.

### Security

If you discover any security related issues, please email soy@miguelpiedrafita.com instead of using the issue tracker.

## Credits

- [Miguel Piedrafita](https://github.com/m1guelpf)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
