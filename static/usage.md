---
layout: default
title: Usage
permalink: /static/usage/
api: Blade
---


Basic
-----

Output a basic view (from views/index.blade.php)

```php
use duncan3dc\Laravel\Blade;

echo Blade::render("index");
```


Multiple Paths
--------------

Check multiple directories for a view (from `/www/views/index.blade.php` if it exists, otherwise `views/index.blade.php`)

```php
Blade::addPath("/www/custom/views");

echo Blade::render("index");
```
