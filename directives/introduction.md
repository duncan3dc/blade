---
layout: default
title: Directives
permalink: /directives/
api: Directives
---

This library extends the standard Blade syntax with a few custom [Directives](https://laravel.com/docs/master/blade#extending-blade).

These are handled by the Directives class, and you can provide your own custom instance by implementing the [DirectivesInterface](../../api/classes/duncan3dc.Laravel.DirectivesInterface.html):

```php
use duncan3dc\Laravel\BladeInstance;
use duncan3dc\Laravel\Directives;

$directives = new Directives;

$blade = new BladeInstance("/var/www/views", "/var/www/cache/views", $directives);
```


You can also use this extra functionality inside the Laravel framework. Just pass your Laravel Blade instance to the registerDirectives() method, like so:

```php
(new duncan3dc\Laravel\Directives)->register(\App::make("blade"));
```

<p class="message-info">The Directives class was introduced in version 4.1.0</p>
