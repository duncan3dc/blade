---
layout: default
title: Namespaces
permalink: /extra/namespaces/
---


The Blade syntax available via this library has been extended to support namespaces.


Declare a namespace for the php generated from the view:

```html
@namespace(duncan3dc\Webapps\Calendar)
```


Import classes for use in the views:

```html
@use(duncan3dc\Helpers\Html)

{!! Html::formatKey("project_title") !!}
```


You can also use this extra functionality inside the Laravel framework. Just pass your Laravel Blade instance to the registerDirectives() method, like so:

```php
\duncan3dc\Laravel\Blade::registerDirectives(\App::make("blade"));
```
