---
layout: default
title: Assets
permalink: /extra/assets/
---


The Blade syntax available via this library has been extended to make asset inclusion easier.

```html
@css('/css/styles.css')
```

The above command will generate the following html:

```html
<link rel='stylesheet' type='text/css' href='/css/styles.css'>
```

However the path is optional ('/css/' is assumed):

```html
@css('styles.css')
```

The extension is also optional ('.css' is assumed):

```html
@css('styles')
```

There is also a javascript command which works in the same way:

```html
@js('/js/resizer.js')
@js('resizer.js')
@js('resizer')
```

The above commands will all generate the following html:

```html
<script type='text/javascript' src='/js/resizer.js'></script>
```



You can also use this extra functionality inside the Laravel framework. Just pass your Laravel Blade instance to the registerDirectives() method, like so:

```php
\duncan3dc\Laravel\Blade::registerDirectives(\App::make("blade"));
```
