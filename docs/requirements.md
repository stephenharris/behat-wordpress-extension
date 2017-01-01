---
currentMenu: requirements
---

# Requirements

Package                              | Minimum required version
------------------------------------ | ------------------------
[Composer](https://getcomposer.org/) | *
[PHP](https://php.net/)              | >= 5.6
[WordPress](https://wordpress.org/)  | >= 4.7


# Suggested extras

Package                              | Minimum required version
------------------------------------ | ------------------------
[Selenium standalone](http://docs.seleniumhq.org/download/)<sup>[1](#SEL)</sup> | >= 3.0.1
[WP-CLI](https://wp-cli.org/)<sup>[2](#WP-CLI)</sup>                            | >= 0.24.0

1. Recommended for testing <a href="http://mink.behat.org/en/latest/guides/drivers.html" id="SEL">websites that require Javascript</a>. Requires the [Mink Selenium2 driver](https://packagist.org/packages/behat/mink-selenium2-driver) in your project.
1. The WP-CLI executable *must* be named `wp` and be within your system's <a href="https://en.wikipedia.org/wiki/PATH_(variable)" id="WP-CLI">$PATH</a>.
