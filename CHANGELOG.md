Changelog
=========

## x.y.z - UNRELEASED

--------

## 4.3.0 - 2018-09-04

### Added

* [Support] Add support for Laravel 5.7.

--------

## 4.2.0 - 2018-02-10

### Added

* [Support] Add support for Laravel 5.6.

### Changed

* [Support] Drop support for PHP 7.0.

--------

## 4.1.0 - 2017-11-06

### Added

* [Directives] The custom directives are now handled by the Directives class.
* [Directives] The assets (css/js) now support full URLs.

--------

## 4.0.0 - 2017-08-30

### Added

* [Support] Add support for Laravel 5.5.
* [BladeInstance] Added support for the if() method from Laravel.
* [BladeInterface] Created an interface for BladeInstance to implement.

### Changed

* [Support] Drop support for Laravel 5.1, 5.2, 5.3 and 5.4.
* [Support] Drop support for PHP 5.6.
* [Support] Drop support for HHVM.

### Removed

* [BladeInstance] Removed the priority parameter from composer() to follow upstream.

--------

## 3.4.0 - 2017-01-28

### Added

* [Support] Add support for Laravel 5.4.
* [BladeInstance] Added support for the replaceNamespace() method from Laravel.

--------

## 3.3.0 - 2016-08-12

### Changed

* [Support] Add support for Laravel 5.3.
* [BladeInstance] Laravel 5.3 has changed the directive() method so that it now strips the surrounding brackets.

--------

## 3.2.0 - 2016-07-31

### Added

* [BladeInstance] Added an directive() method to add custom directives.

--------

## 3.1.0 - 2016-06-23

### Added

* [BladeInstance] Added an extend() method to register custom compilers.

--------

## 3.0.0 - 2015-12-27

### Added

* [BladeInstance] Implement the laravel contract interface.

### Changed

* [BladeInstance] The view and cache paths are now required parameters on construction.
* [Support] Add support for Laravel 5.2.
* [Support] Drop support for PHP 5.5.

--------

## 2.2.0 - 2015-06-27

### Changed

* [Blade/BladeInstance] Rename extendBlade() to registerDirectives().
* [Support] Add support for Laravel 5.1.
* [Support] Drop support for PHP 5.4.

--------

## 2.1.6 - 2015-06-27

### Fixed

* [Support] Tighten Laravel dependencies as they don't follow semver very strictly.

--------

## 2.1.5 - 2015-06-03

### Added

* [Blade/BladeInstance] Add css/js asset syntax.
* [Blade] Allow the instance used by the static class to be get/set.

--------

## 2.1.0 - 2015-04-29

### Added

* [Blade/BladeInstance] Allow composers/creators to be registered.
* [Blade/BladeInstance] Add support for sharing data across views.
* [Support] Add support for PHP 7.

--------

## 2.0.0 - 2015-02-04

### Added

* [BladeInstance] Created a BladeInstance class to avoid static calls.

### Changed

* [Support] Base this version on Laravel 5.0 which has a new escaping content style.
