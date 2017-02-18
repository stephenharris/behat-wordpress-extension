# FAQs

## Javascript
* If you are using [Selenium](http://docs.seleniumhq.org/download/) to run Javascript tests, and you access your WordPress site over HTTPS, *and* it has a self-signed certificate, you will need to manually configure the web browser to accept that certificate.

## WP-CLI
* If you are using the WP-CLI driver to [connect to a remote WordPress site over SSH](https://wp-cli.org/blog/version-0.24.0.html#but-wait-whats-the-ssh-in-there), WordHat assumes the remote server is Linux-like, with a shell that provides [GNU Coreutils](https://www.gnu.org/software/coreutils/coreutils.html).
