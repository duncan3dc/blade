blade
=====

Use Laravel Blade templates as a standalone component without the full Laravel framework

[![Build Status](https://travis-ci.org/duncan3dc/blade.svg?branch=master)](https://travis-ci.org/duncan3dc/blade)
[![Latest Stable Version](https://poser.pugx.org/duncan3dc/blade/version.svg)](https://packagist.org/packages/duncan3dc/blade)


Versions
--------
* For Laravel 5 style escaping `{!!` = raw, and `{{` = escaped, use version 2.* of this project.
* For Laravel 4 style escaping `{{` = raw, and `{{{` = escaped, use version 1.* of this project.


Documentation
-------------

There is are two classes available with a namespace of duncan3dc\Laravel. One is for regular usage (`BladeInstance`) and one for static calls (`Blade`) facade style.
```php
use duncan3dc\Laravel\Blade;
use duncan3dc\Laravel\BladeInstance;
```

The recommended way of using this library is the `BladeInstance` class.  
The paths used for view templates and for cached files can be set by passing them to the constructor:
```php
$blade = new BladeInstance("/var/www/views", "/var/www/cache/views");
```

If they are not passed, or the static `Blade` class is used then the [Env helper](https://github.com/duncan3dc/php-helpers) is used to resolve paths.  
The following paths are used:
* views - Default directory to search for *.blade.php templates
* cache/views - Directory to cache generated php code from the templates


Some minor extensions to blade syntax have been made, such as supporting namespaces (see examples below)


Examples
--------

Output a basic view (from views/index.blade.php)
```php
echo $blade->render("index");
```


Check multiple directories for a view (from /var/www/views/index.blade.php if it exists, otherwise /var/www/custom/views/index.blade.php)
```php
$blade = new BladeInstance("/var/www/views");
$blade->addPath("/var/www/custom/views");
echo $blade->render("index");
```


Declare a namespace for the php generated from the view
```php
@namespace(duncan3dc\Webapps\Calendar)
```


Import classes for use in the views
```php
@use(duncan3dc\Helpers\Html)

{!! Html::formatKey("project_title") !!}
```


If you need an Illuminate\View\View object there is also a make() method available
```php
$view = $blade->make("index");
```



Static Usage
------------

Output a basic view (from views/index.blade.php)
```php
echo Blade::render("index");
```


Output a view from a different directory (from /home/webapp/views/index.blade.php)
```php
use duncan3dc\Helpers\Env;

Env::usePath("/home/webapp");
echo Blade::render("index");
```



Laravel
-------

You can also use the extra functionality provided by this library inside the Laravel framework.  
Just pass your Blade instance to the registerDirectives() method.
```php
\duncan3dc\Laravel\Blade::registerDirectives(\App::make("blade"));
```
