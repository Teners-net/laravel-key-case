Laravel Key Case
=
Middleware for automatic case transformation of request and response data in Laravel applications.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/teners/laravel-key-case.svg?style=flat-square)](https://packagist.org/packages/teners/laravel-key-case)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/Teners-net/laravel-key-case/tests.yml?branch=main&label=Tests)](https://github.com/Teners-net/laravel-key-case/actions?query=workflow%3ATests+branch%3Amain)
[![issues](https://img.shields.io/github/issues/Teners-net/laravel-key-case?style=flat-square)](https://github.com/Teners-net/laravel-key-case/issues)
[![stars](https://img.shields.io/github/stars/Teners-net/laravel-key-case?style=flat-square)](https://github.com/Teners-net/laravel-key-case/issues)
[![GitHub license](https://img.shields.io/github/license/Teners-net/laravel-key-case?style=flat-square)](https://github.com/Teners-net/laravel-key-case/blob/main/LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/teners/laravel-key-case.svg?style=flat-square)](https://packagist.org/packages/teners/laravel-key-case)


## Why?
It is a common convention to work with camel case in Javascript and most front-end technologies and snake case in PHP (Backend), this package makes that a breeze as you can work with your data columns just as you like in laravel and your responces or request data are automatically converted to any case.

## Installation

Install Laravel Key Case, run the following command in your terminal:

```bash
composer require teners/laravel-key-case
```

#### Publish the package configuration file

```bash
php artisan vendor:publish --provider="Teners\LaravelKeyCase\LaravelKeyCaseServiceProvider" --tag="key-case-config"
```
You can then customize what case to use for each of the request and the response data.

#### Use the middlewares

This package comes with two middlewares
- `TransformResponseMiddleware`: Transforms response data
- `TransformRequestMiddleware`: Transforms request data

You can register their aliases (or use them dirrectly on the api route group if you are using them through the api) for easy reference elsewhere in your app:

In Laravel 11 open `/bootstrap/app.php` and register them there:

```php
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(append: [
            \Teners\LaravelKeyCase\Http\Middleware\TransformResponseMiddleware:class,
        ]);
    })

    // Or to use on individual routes
    
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'convert-response-key' => \Teners\LaravelKeyCase\Http\Middleware\TransformResponseMiddleware:class,
        ]);
    })
```

In Laravel 9 and 10 you can add them in `/app/Http/Kernel.php`:

```php
    protected $middlewareGroups = [
        'web' => [
            ...
        ],

        'api' => [
            ...
            TransformResponseMiddleware::class,
        ],
    ];

    // Or to use on individual routes

    // Laravel 9 uses $routeMiddleware = [
    //protected $routeMiddleware = [
    // Laravel 10+ uses $middlewareAliases = [
    protected $middlewareAliases = [
        // ...
        'convert-response-key' => \Teners\LaravelKeyCase\Http\Middleware\TransformResponseMiddleware:class,
    ];
```

## Contributions
Contributions are **welcome** via Pull Requests on [Github](https://github.com/Teners-net/laravel-key-case).
- Please document any change you made as neccesary in the [README.md](README.md).
- Pleas make only one pull request per feature/fix.

## Issues
Please report any issue you encounter in using the package through the [Github Issues](https://github.com/Teners-net/laravel-key-case/issues) tab.

## Testing

``` bash
composer test
```

## Credits

- [Emmanuel Adesina](https://github.com/ThePlatinum)

### Contributors

Contributors list will be added here

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.