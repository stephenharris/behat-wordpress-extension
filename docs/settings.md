---
currentMenu: settings
---

# Settings

Behat uses [YAML](https://en.wikipedia.org/wiki/YAML) for its configuration file.


## PaulGibbs\WordpressBehatExtension

Extension `PaulGibbs\WordpressBehatExtension` integrates WordPress into Behat. These are its configuration options:

```YAML
PaulGibbs\WordpressBehatExtension:
  default_driver: wpcli
  path: ~

  # User settings.
  users:
    admin:
      username: admin
      password: admin
    editor:
      username: editor
      password: editor
    author:
      username: author
      password: author
    contributor:
      username: contributor
      password: contributor
    subscriber:
      username: subscriber
      password: subscriber

  # WordPress settings.
  permalinks:
    author_archive: author/%s/

  # Driver settings.
  wpcli:
    alias: dev
```

Option             | Default value | Description
-------------------| ------------- | -----------
`default_driver`   | "wpcli"       | The [driver](drivers.html) to use ("wpcli", "wpapi", "blackbox").
`path`             | null          | _Required_. Path to WordPress files.
`users.*`          | _see example_ | _Optional_. Keys must match names of WordPress roles.
`permalinks.*`     | _see example_ | _Optional_. Permalink pattern for the specified kind of link.<br>`%s` is replaced with an ID/object name, as appropriate.
`wpcli.alias`      | null          | _Optional_. [WP-CLI alias](https://wp-cli.org/commands/cli/alias/) (preferred over `wpcli.path`).


## Behat\MinkExtension


```YAML
Behat\MinkExtension:
  # Recommended settings.
  base_url: ~
```

Option             | Default value | Description
-------------------| ------------- | -----------
`base_url`         | _null_        | If you use relative paths in your tests, this defines a URL to use as the basename.

Extension `Behat\MinkExtension` integrates Mink into Behat. [Visit its website](http://mink.behat.org/en/latest/) for more information.
