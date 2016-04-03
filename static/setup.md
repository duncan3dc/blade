---
layout: default
title: Setup
permalink: /static/setup/
api: Blade
---

<p class="message-info">The recommended way of using this library is the <a href='../../instance/setup/'>BladeInstance</a> class.</p>


Paths
-----

When using the static Blade class, the paths are guessed by assuming this library has been installed in the default composer vendor directory.

```
/composer.json
/vendor
/views [The directory to look for *.blade.php templates]
/cache/views [The directory to cache compiled php in]
```
