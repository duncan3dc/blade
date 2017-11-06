---
layout: default
title: Assets
permalink: /directives/assets/
---


The Blade syntax available via this library has been extended to make asset inclusion easier.

```html
@css("/css/styles.css")
```

The above command will generate the following html:

```html
<link rel='stylesheet' type='text/css' href='/css/styles.css'>
```

However the path is optional ("/css/" is assumed):

```html
@css("styles.css")
```

The extension is also optional (".css" is assumed):

```html
@css("styles")
```

There is also a javascript command which works in the same way:

```html
@js("/js/resizer.js")
@js("resizer.js")
@js("resizer")
```

The above commands will all generate the following html:

```html
<script type='text/javascript' src='/js/resizer.js'></script>
```


You can customer the default path like so:
```php
use duncan3dc\Laravel\BladeInstance;
use duncan3dc\Laravel\Directives;

$directives = (new Directives)
    ->withCss("assets/css")
    ->withJs("assets/js");

$blade = new BladeInstance("/var/www/views", "/var/www/cache/views", $directives);
```


Or disable them:
```php
$directives = (new Directives)
    ->withoutCss()
    ->withoutJs();
```
