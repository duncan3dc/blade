Changelog
=========

## x.y.z - UNRELEASED

--------

## 3.0.0 - 2015-12-27

### Added

* [BladeInstance] Created a BladeInstance class to avoid static calls.

### Changed

* [Support] Base this version on Laravel 5.0 which has a new escaping content style.

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
commit 8166008ba8acbc804fc50425935c6982b573c5c5
Author: Craig Duncan <git@duncanc.co.uk>
Date:   Sun Dec 20 22:12:08 2015 +0000

    Add some tests that ensure wrapped methods call correctly

commit 7faeb1219bda3f654932557da21ecdcc0ca9992a
Author: Craig Duncan <git@duncanc.co.uk>
Date:   Wed Nov 4 16:29:39 2015 +0000

    Implement the laravel contract

commit dfa57be40609c991a41935c25fb00b2395ee2824
Author: Craig Duncan <git@duncanc.co.uk>
Date:   Tue Dec 1 09:20:16 2015 +0000

    Add some tests for inheritance

commit e964642b7241aef96ab47c65002b24e61b93a7e2
Author: Craig Duncan <git@duncanc.co.uk>
Date:   Sun Nov 1 15:46:20 2015 +0000

    Drop support for php5.5

commit 77547644fa21fdd2c897bd02e5729dd299c7c3b6
Author: Craig Duncan <git@duncanc.co.uk>
Date:   Sat Oct 31 13:04:27 2015 +0000

    Minor cleanup

commit b5d2483acf9f75d7564ad924082583f4b805cd8d
Author: Craig Duncan <git@duncanc.co.uk>
Date:   Sun Oct 25 20:10:04 2015 +0000

    Add support for Laravel 5.2
    
    Also update the dev dependencies

commit 8023c4642dd5ae4a63c9acc56291779352d7d070
Author: Craig Duncan <git@duncanc.co.uk>
Date:   Sun Oct 25 20:02:43 2015 +0100

    Remove the dependency on duncan3dc/helpers

commit 383917f85bb897a13e653812e11587019fb77041
Author: Craig Duncan <git@duncanc.co.uk>
Date:   Sat Oct 24 18:07:19 2015 +0100

    Require the paths to be specified when creating a new instance

commit 6b55d71e12a7654f4cb8a2cc65f8f06b637beda8
Author: Craig Duncan <git@duncanc.co.uk>
Date:   Fri Oct 23 18:12:31 2015 +0100

    Replace all the static mappings with a generic handler

commit 252751066e3d7298bcd0bd02b53dd4479a2eb88b
Author: Craig Duncan <git@duncanc.co.uk>
Date:   Sat Oct 17 10:55:32 2015 +0100

    Correct the name of the phpunit definition file

commit 20fce78dff32c0f73b3672a3e44c2ab16f57271b
Author: Craig Duncan <git@duncanc.co.uk>
Date:   Sat Jul 25 18:40:42 2015 +0100

    Use the local phpunit version, not the travis default
