---
layout: default
title: Usage
permalink: /instance/usage/
api: BladeInstance
---


Output a basic view (from views/index.blade.php)

```php
echo $blade->render("index");
```


Multiple Paths
--------------

Check multiple directories for a view (from `/www/views/index.blade.php` if it exists, otherwise `/www/custom/views/index.blade.php`)

```php
$blade = new BladeInstance("/www/views");

$blade->addPath("/www/custom/views");

echo $blade->render("index");
```
