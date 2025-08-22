# Laravel Key Case

A high-performance Laravel package that automatically transforms request and response data keys between different naming conventions (camelCase ↔ snake_case, kebab-case, etc.).

[![Latest Version on Packagist](https://img.shields.io/packagist/v/teners/laravel-key-case.svg?style=flat-square)](https://packagist.org/packages/teners/laravel-key-case)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/Teners-net/laravel-key-case/tests.yml?branch=main&label=Tests)](https://github.com/Teners-net/laravel-key-case/actions?query=workflow%3ATests+branch%3Amain)
[![Issues](https://img.shields.io/github/issues/Teners-net/laravel-key-case?style=flat-square)](https://github.com/Teners-net/laravel-key-case/issues)
[![Stars](https://img.shields.io/github/stars/Teners-net/laravel-key-case?style=flat-square)](https://github.com/Teners-net/laravel-key-case/stargazers)
[![GitHub License](https://img.shields.io/github/license/Teners-net/laravel-key-case?style=flat-square)](https://github.com/Teners-net/laravel-key-case/blob/main/LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/teners/laravel-key-case.svg?style=flat-square)](https://packagist.org/packages/teners/laravel-key-case)

## 🎯 Why Laravel Key Case?

Bridge the gap between frontend and backend naming conventions effortlessly. Work with your preferred naming style in both JavaScript (camelCase) and PHP (snake_case) without manual conversion.

## 📦 Installation

Install via Composer:

```bash
composer require teners/laravel-key-case
```

### Publish Configuration (Optional)

```bash
php artisan vendor:publish --provider="Teners\LaravelKeyCase\LaravelKeyCaseServiceProvider" --tag="key-case-config"
```

## Quick Start

### Laravel 11

Register middleware in `bootstrap/app.php`:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Apply to all API routes
        $middleware->api(append: [
            \Teners\LaravelKeyCase\Http\Middleware\TransformResponseMiddleware::class,
            \Teners\LaravelKeyCase\Http\Middleware\TransformRequestMiddleware::class,
        ]);

        // Or register aliases for individual routes
        $middleware->alias([
            'transform-request' => \Teners\LaravelKeyCase\Http\Middleware\TransformRequestMiddleware::class,
            'transform-response' => \Teners\LaravelKeyCase\Http\Middleware\TransformResponseMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

### Older laravel versions

Add to `app/Http/Kernel.php`:

```php
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'web' => [
            // ... other middleware
        ],

        'api' => [
            // ... other middleware
            \Teners\LaravelKeyCase\Http\Middleware\TransformResponseMiddleware::class,
            \Teners\LaravelKeyCase\Http\Middleware\TransformRequestMiddleware::class,
        ],
    ];

    // For individual route usage
    protected $middlewareAliases = [
        // ... other aliases
        'transform-request' => \Teners\LaravelKeyCase\Http\Middleware\TransformRequestMiddleware::class,
        'transform-response' => \Teners\LaravelKeyCase\Http\Middleware\TransformResponseMiddleware::class,
    ];
}
```

## Configuration

The package works with zero configuration, but you can customize it by publishing the config file:

```php
<?php

return [
    /**
     * Response Key Case - Transform API response keys
     * Options: camel, snake, kebab, studly, lower, upper, ucfirst, ucwords, singular, plural, slug, title
     */
    'response_case' => env('RESPONSE_CASE', 'camel'),

    /**
     * Request Key Case - Transform incoming request keys
     * Options: camel, snake, kebab, studly, lower, upper, ucfirst, ucwords, singular, plural, slug, title
     */
    'request_case' => env('REQUEST_CASE', 'snake'),

    /**
     * Performance Settings
     */
    'max_depth' => env('KEY_CASE_MAX_DEPTH', 10),

    /**
     * Route Exclusions
     */
    'ignore' => [
        'health-check',
    ],

    'ignore_request' => [],
    'ignore_response' => [],
];
```

## 📖 Usage Examples

### Basic API Transformation

**Frontend (JavaScript)**

```javascript
// Send camelCase data
const userData = {
    firstName: "John",
    lastName: "Doe",
    emailAddress: "john@example.com",
};

fetch("/api/users", {
    method: "POST",
    body: JSON.stringify(userData),
});
```

**Backend (Laravel)**

```php
<?php

// Automatically received as snake_case
public function store(Request $request)
{
    $validated = $request->validate([
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'email_address' => 'required|email',
    ]);

    User::create($validated);

    return response()->json([
        'user_id' => $user->id,
        'created_at' => $user->created_at,
    ]);
    // Automatically transformed to camelCase: {"userId": 1, "createdAt": "2024-01-01T12:00:00Z"}
}
```

### Route-Specific Control

```php
<?php

// Apply to specific routes
Route::middleware(['transform-response'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
});

// Skip transformation for specific routes
Route::get('/legacy-api/data', function () {
    // This route will be ignored
});
```

## Supported Case Types

| Case Type  | Example               | Use Case              |
| ---------- | --------------------- | --------------------- |
| `camel`    | `firstName`           | JavaScript, JSON APIs |
| `snake`    | `first_name`          | PHP, Database columns |
| `kebab`    | `first-name`          | URLs, CSS classes     |
| `studly`   | `FirstName`           | Class names           |
| `lower`    | `firstname`           | Simple keys           |
| `upper`    | `FIRSTNAME`           | Constants             |
| `ucfirst`  | `Firstname`           | Sentences             |
| `ucwords`  | `First Name`          | Titles                |
| `singular` | `user` (from `users`) | Model names           |
| `plural`   | `users` (from `user`) | Collections           |
| `slug`     | `first-name`          | URLs                  |
| `title`    | `First Name`          | Display text          |

### Error Resilience

-   Non-blocking - errors won't break your requests

## 🧪 Testing

Run the test suite:

```bash
# Run all tests
composer test
```

## Contributions

Contributions are **welcome** via Pull Requests on [Github](https://github.com/Teners-net/laravel-key-case).

-   Please document any change you made as neccesary in the README.md.
-   Follow PSR-12 coding standards
-   Write tests for new features
-   Update documentation for any changes
-   Make one pull request per feature/fix
-   Ensure all tests pass

## Issues

Please report any issue you encounter in using the package through the [Github Issues](https://github.com/Teners-net/laravel-key-case/issues) tab.

When reporting issues, please include:

-   Laravel version
-   PHP version
-   Package version
-   Code example
-   Error messages

## Testing

To run tests, use:

```bash
composer test
```

## Credits

-   [Emmanuel Adesina](https://github.com/ThePlatinum)

Contributors list will be added here

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

<div align="center" style="margin-top: 50px">
<p>Made with ❤️ by <a href="https://teners.net">Teners</a>  - if this package helped you ⭐ <a href="https://github.com/Teners-net/laravel-key-case">Star us on GitHub</a></p>
</div>
