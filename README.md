blade
=====

Use Laravel Blade templates as a standalone component without the full Laravel framework

[![Build Status](https://travis-ci.org/duncan3dc/blade.svg?branch=master)](https://travis-ci.org/duncan3dc/blade)
[![Latest Stable Version](https://poser.pugx.org/duncan3dc/blade/version.svg)](https://packagist.org/packages/duncan3dc/blade)


Documentation
-------------

There is are two classes available with a namespace of duncan3dc\Laravel. One is for regular usage (`BladeInstance`) and one for static calls (`Blade`) facade style.
```php
use duncan3dc\Laravel\Blade;
use duncan3dc\Laravel\BladeInstance;
```

The recommended way of using this library is the `BladeInstance` class.  
The paths used for view templates and for cached files are set by passing them to the constructor:
```php
$blade = new BladeInstance("/var/www/views", "/var/www/cache/views");
```

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

When using the static `Blade` class, the paths are guessed by assuming this library has been installed in the default composer `vendor` directory.  
/var/www
+-- composer.json
+-- vendor
+-- views
+-- cache
|   +-- views



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
