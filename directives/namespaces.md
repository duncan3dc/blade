---
layout: default
title: Namespaces
permalink: /directives/namespaces/
---

Declare a namespace for the php generated from the view:

```html
@namespace(duncan3dc\Webapps\Calendar)
```


Import classes for use in the views:

```html
@use(duncan3dc\Helpers\Html)

{!! Html::formatKey("project_title") !!}
```


You can disable these directives like so:
```php
use duncan3dc\Laravel\BladeInstance;
use duncan3dc\Laravel\Directives;

$directives = (new Directives)
    ->withoutNamespace()
    ->withoutUse();

$blade = new BladeInstance("/var/www/views", "/var/www/cache/views", $directives);
```
