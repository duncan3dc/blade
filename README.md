blade
=====

Use Laravel Blade templates as a standalone component without the full Laravel framework

[![Build Status](https://travis-ci.org/duncan3dc/blade.svg?branch=master)](https://travis-ci.org/duncan3dc/blade)
[![Latest Stable Version](https://poser.pugx.org/duncan3dc/blade/version.svg)](https://packagist.org/packages/duncan3dc/blade)


Documentation
-------------

There is a single class available with a namespace of duncan3dc\Laravel
```php
use duncan3dc\Laravel\Blade;
```

The class uses the [Env helper](https://github.com/duncan3dc/php-helpers) to resolve paths.  
The following paths are used:
* views - Default directory to search for *.blade.php templates
* cache/views - Directory to cache generated php code from the templates

Some minor extensions have been made, such as supporting namespaces (see examples below)


Examples
--------

Output a basic view (from views/index.blade.php)
```php
echo Blade::render("index");
```


Output a view from a different directory (from /var/www/views/index.blade.php)
```php
use duncan3dc\Helpers\Env;

Env::usePath("/var/www/views");
echo Blade::render("index");
```


Check multiple directories for a view (from views/index.blade.php if it exists, otherwise /var/www/views/index.blade.php)
```php

Blade::addPath("/var/www/views");
echo Blade::render("index");
```


Declare a namespace for the php generated from the view
```php
@namespace(duncan3dc\Webapps\Calendar)
```


Import classes for use in the views
```php
@use(duncan3dc\Helpers\Html)

{{{ Html::formatKey("project_title") }}}
```


If you need an Illuminate\View\View object there is also a make() method available
```php
$view = Blade::make("index");
```


You can also use the extra functionality provided by this class inside the Laravel framework.  
Just pass your Blade instance to the extendBlade() method.
```php
Blade::extendBlade(App::make("blade"));
```
