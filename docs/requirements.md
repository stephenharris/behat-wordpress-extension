---
currentMenu: requirements
---

# Requirements

* A [WordPress](https://wordpress.org/) site, running version 4.7+.
* PHP 5.6+, with the [cURL extension](https://curl.haxx.se/libcurl/php/install.html).
* [Composer](https://getcomposer.org/).
* [Selenium](http://docs.seleniumhq.org/download/) (the "Standalone Server" version).


## Driver requirements

The [WP-CLI driver](drivers.html) requires [WP-CLI](http://wp-cli.org/) (version 0.24.0 or newer), to be installed globally:
  ```Shell
  curl -o wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
  chmod +x wp
  ```

  The executable *must* be named `wp` and be within your system's [$PATH](https://en.wikipedia.org/wiki/PATH_(variable)).
