---
layout: default
title: Setup
permalink: /instance/setup/
api: BladeInstance
---


Paths
-----

The paths used for view templates and for cached files are set by passing them to the constructor:

```php
use duncan3dc\Laravel\Blade;

$blade = new BladeInstance("/var/www/views", "/var/www/cache/views");
```


Contract
--------

The `BladeInstance` class follows the [contracts](https://laravel.com/docs/master/contracts) philosophy of Laravel and implements the [Factory](https://github.com/illuminate/contracts/blob/master/View/Factory.php) interface.

This means that you should be able to use `BladeInstance` anywhere that is currently coded to accept a regular Laravel view factory.
