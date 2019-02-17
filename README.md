Clara Sentinel
===============

Cartalyst/Sentinel made for Clara.

## Installation

```php
composer require ceddyg/clara-sentinel
```

Add to your providers in 'config/app.php'
```php
CeddyG\ClaraSentinel\SentinelServiceProvider::class,
```

Then to publish the files.
```php
php artisan vendor:publish --provider="CeddyG\ClaraSentinel\SentinelServiceProvider"
```
