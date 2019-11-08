blade
=====

Use Laravel Blade templates as a standalone component without the full Laravel framework

Full documentation is available at http://duncan3dc.github.io/blade/  
PHPDoc API documentation is also available at [http://duncan3dc.github.io/blade/api/](http://duncan3dc.github.io/blade/api/namespaces/duncan3dc.Laravel.html)  

[![release](https://poser.pugx.org/duncan3dc/blade/version.svg)](https://packagist.org/packages/duncan3dc/blade)
[![build](https://travis-ci.org/duncan3dc/blade.svg?branch=master)](https://travis-ci.org/duncan3dc/blade)
[![coverage](https://codecov.io/gh/duncan3dc/blade/graph/badge.svg)](https://codecov.io/gh/duncan3dc/blade)


Quick Examples
--------------

Output the view from `/var/www/views/index.blade.php`:
```php
use duncan3dc\Laravel\BladeInstance;

$blade = new BladeInstance("/var/www/views", "/var/www/cache/views");

echo $blade->render("index");
```

There is also a static class available:
```php
use duncan3dc\Laravel\Blade;

echo Blade::render("index");
```


_Read more at http://duncan3dc.github.io/blade/_  


Changelog
---------
A [Changelog](CHANGELOG.md) has been available since version 2.0.0


Where to get help
-----------------
Found a bug? Got a question? Just not sure how something works?  
Please [create an issue](//github.com/duncan3dc/blade/issues) and I'll do my best to help out.  
Alternatively you can catch me on [Twitter](https://twitter.com/duncan3dc)


## duncan3dc/blade for enterprise

Available as part of the Tidelift Subscription

The maintainers of duncan3dc/blade and thousands of other packages are working with Tidelift to deliver commercial support and maintenance for the open source dependencies you use to build your applications. Save time, reduce risk, and improve code health, while paying the maintainers of the exact dependencies you use. [Learn more.](https://tidelift.com/subscription/pkg/packagist-duncan3dc-blade?utm_source=packagist-duncan3dc-blade&utm_medium=referral&utm_campaign=readme)
