# FAQs

## Composer
* WordHat does not support a custom `vendor-bin` for Composer if you are using the [WP-CLI driver](drivers.md). [#16](https://github.com/paulgibbs/behat-wordpress-extension/issues/16)

## Javascript
* If you are using [Selenium](http://docs.seleniumhq.org/download/) to run Javascript tests, and you access your WordPress site over HTTPS, *and* it has a self-signed certificate, you will need to manually configure the web browser to accept that certificate.

## WP-CLI
* If your WordPress site causes PHP notices or errors during WP-CLI execution, or prints to `error_log`, Behat will interpret this as an error (`UnexpectedValueException`). The solution is to fix the code in the plugin or theme.
* If you are using the WP-CLI driver to [connect to a remote WordPress site over SSH](https://wp-cli.org/blog/version-0.24.0.html#but-wait-whats-the-ssh-in-there), WordHat assumes the remote server is Linux-like, with a shell that provides [GNU Coreutils](https://www.gnu.org/software/coreutils/coreutils.html).
