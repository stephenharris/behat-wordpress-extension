# Drivers

WordHat provides a range of drivers for interacting with the WordPress site you are testing. A driver represents and manages the connection between the Behat and WordPress environments. Different drivers support different features.

* The **WP-CLI** driver -- the default -- uses [WP-CLI](http://wp-cli.org/) to communicate with WordPress.
* The **WordPress API** driver loads WordPress in the same PHP context as Behat.
* The **Blackbox** driver interacts with WordPress through a web browser, in an unpriviledged context.

To specify which driver to use for your tests, set [`default_driver`](settings.md) in your `behat.yml` file.

Feature                                  | WP-CLI                     | WordPress API | Blackbox
---------------------------------------- | -------------------------- | ------------- | --------
Publish posts and comments.              | Yes                        | Yes           | No
Create terms for taxonomy.               | Yes                        | Yes           | No
Create users.                            | Yes                        | Yes           | No
Manage plugins.                          | Yes                        | Yes           | No
Switch theme.                            | Yes                        | Yes           | No
Clear cache.                             | Yes                        | Yes           | No
Database import/export.                  | Yes                        | No            | No
Run tests and site on different servers. | Yes<sup>[1](#WP-CLI)</sup> | No            | Yes
Database transactions.                   | No                         | Yes           | No

1. WP-CLI <a href="https://wp-cli.org/blog/version-0.24.0.html#but-wait-whats-the-ssh-in-there" id="WP-CLI">supports SSH connections</a> to remote WordPress sites.
