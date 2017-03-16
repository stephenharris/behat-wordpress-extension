<?php
namespace PaulGibbs\WordpressBehatExtension\Driver;

use RuntimeException;
use UnexpectedValueException;
use function PaulGibbs\WordpressBehatExtension\Util\is_wordpress_error;

/**
 * Connect Behat to WordPress by loading WordPress directly into the global scope.
 */
class WpapiDriver extends BaseDriver
{
    /**
     * Path to WordPress' files.
     *
     * @var string
     */
    protected $path = '';

    /**
     * WordPres database object.
     *
     * @var \wpdb
     */
    protected $wpdb = null;

    /**
     * Constructor.
     *
     * @param string $path Absolute path to WordPress site's files.
     */
    public function __construct($path)
    {
        $this->path = realpath($path);
    }

    /**
     * Set up anything required for the driver.
     *
     * Called when the driver is used for the first time.
     */
    public function bootstrap()
    {
        if (! defined('ABSPATH')) {
            define('ABSPATH', "{$this->path}/");
        }

        $_SERVER['DOCUMENT_ROOT']   = $this->path;
        $_SERVER['HTTP_HOST']       = '';
        $_SERVER['REQUEST_METHOD']  = 'GET';
        $_SERVER['REQUEST_URI']     = '/';
        $_SERVER['SERVER_NAME']     = '';
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';

        if (! file_exists("{$this->path}/index.php")) {
            throw new RuntimeException(sprintf('WordPress API driver cannot find WordPress at %s.', $this->path));
        }

        // "Cry 'Havoc!' and let slip the dogs of war".
        require_once "{$this->path}/wp-blog-header.php";

        if (! function_exists('activate_plugin')) {
            require_once "{$this->path}/wp-admin/includes/plugin.php";
            require_once "{$this->path}/wp-admin/includes/plugin-install.php";
        }

        $this->wpdb            = $GLOBALS['wpdb'];
        $this->is_bootstrapped = true;
    }

    /**
     * Clear object cache.
     */
    public function clearCache()
    {
        wp_cache_flush();
    }

    /**
     * Activate a plugin.
     *
     * @param string $plugin
     */
    public function activatePlugin($plugin)
    {
        $path = $this->getPlugin($plugin);
        if (! $path) {
            throw new RuntimeException("WordPress API driver cannot find the plugin: {$plugin}.");
        }

        activate_plugin($path);
    }

    /**
     * Deactivate a plugin.
     *
     * @param string $plugin
     */
    public function deactivatePlugin($plugin)
    {
        $path = $this->getPlugin($plugin);
        if (! $path) {
            throw new RuntimeException("WordPress API driver cannot find the plugin: {$plugin}.");
        }

        deactivate_plugins($path);
    }

    /**
     * Switch active theme.
     *
     * @param string $theme
     */
    public function switchTheme($theme)
    {
        $the_theme = wp_get_theme($theme);
        if (! $the_theme->exists()) {
            return;
        }

        switch_theme($the_theme->get_template());
    }

    /**
     * Create a term in a taxonomy.
     *
     * @param string $term
     * @param string $taxonomy
     * @param array  $args     Optional. Set the values of the new term.
     * @return array {
     *     @type int    $id   Term ID.
     *     @type string $slug Term slug.
     * }
     */
    public function createTerm($term, $taxonomy, $args = [])
    {
        $args     = wp_slash($args);
        $term     = wp_slash($term);
        $new_term = wp_insert_term($term, $taxonomy, $args);

        if (is_wordpress_error($new_term)) {
            throw new UnexpectedValueException(
                sprintf(
                    'WordPress API driver failed creating a new term: %s',
                    $new_term->get_error_message()
                )
            );
        }

        return array(
            'id'   => $new_term['term_id'],
            'slug' => get_term($new_term['term_id'], $taxonomy)->slug,
        );
    }

    /**
     * Delete a term from a taxonomy.
     *
     * @param int    $term_id
     * @param string $taxonomy
     */
    public function deleteTerm($term_id, $taxonomy)
    {
        $result = wp_delete_term($term_id, $taxonomy);

        if (is_wordpress_error($result)) {
            throw new UnexpectedValueException(
                sprintf(
                    'WordPress API driver failed deleting a new term: %s',
                    $result->get_error_message()
                )
            );
        }
    }

    /**
     * Create content.
     *
     * @param array $args Set the values of the new content item.
     * @return array {
     *     @type int    $id   Content ID.
     *     @type string $slug Content slug.
     * }
     */
    public function createContent($args)
    {
        $args = wp_slash($args);
        $post = wp_insert_post($args);

        if (is_wordpress_error($post)) {
            throw new UnexpectedValueException(
                sprintf(
                    'WordPress API driver failed creating new content: %s',
                    $post->get_error_message()
                )
            );
        }

        $post = get_post($post);

        return array(
            'id'   => (int) $post->ID,
            'slug' => $post->post_name,
            'url'  => get_permalink($post)
        );
    }

    /**
     * Delete specified content.
     *
     * @param int   $id   ID of content to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteContent($id, $args = [])
    {
        $result = wp_delete_post($id, isset($args['force']));

        if (! $result) {
            throw new UnexpectedValueException('WordPress API driver failed deleting content.');
        }
    }

    /**
     * Get content from its title.
     *
     * @param string $title The title of the content to get
     * @param string|array Post type(s) to consider when searching for the content
     * @return array {
     *     @type int    $id   Content ID.
     *     @type string $slug Content slug.
     *     @type string $url Content url.
     * }
     * @throws \UnexpectedValueException If post does not exist
     */
    public function getContentFromTitle($title, $post_type = null)
    {
        if ($post_type === null) {
            $post_type = get_post_types('', 'names');
        }

        $post_type = (array) $post_type;

        $post = get_page_by_title($title, OBJECT, $post_type);

        if (! $post) {
            throw new UnexpectedValueException(
                sprintf('Post "%s" of post type %s not found', $title, implode('/', $post_type))
            );
        }
        return array(
            'id'   => (int) $post->ID,
            'slug' => $post->post_name,
            'url'  => get_permalink($post)
        );
    }

    /**
     * Create a comment.
     *
     * @param array $args Set the values of the new comment.
     * @return array {
     *     @type int $id Content ID.
     * }
     */
    public function createComment($args)
    {
        $comment_id = wp_new_comment($args);

        if (! $comment_id) {
            throw new UnexpectedValueException('WordPress API driver failed creating a new comment.');
        }

        return array('id' => $comment_id);
    }

    /**
     * Delete specified comment.
     *
     * @param int   $id   ID of comment to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteComment($id, $args = [])
    {
        $result = wp_delete_comment($id, isset($args['force']));

        if (! $result) {
            throw new UnexpectedValueException('WordPress API driver failed deleting a comment.');
        }
    }

    /**
     * Create a user.
     *
     * @param string $user_login User login name.
     * @param string $user_email User email address.
     * @param array  $args       Optional. Extra parameters to pass to WordPress.
     * @return array {
     *     @type int    $id   User ID.
     *     @type string $slug User slug (nicename).
     * }
     */
    public function createUser($user_login, $user_email, $args = [])
    {
        $user     = compact($user_login, $user_email);
        $args     = array_merge(wp_slash($user), wp_slash($args));
        $new_user = wp_insert_user($args);

        if (is_wordpress_error($new_user)) {
            throw new UnexpectedValueException(
                sprintf(
                    'WordPress API driver failed creating new user: %s',
                    $new_user->get_error_message()
                )
            );
        }

        return array(
            'id'   => $new_user,
            'slug' => get_userdata($new_user)->user_nicename,
        );
    }

    /**
     * Delete a user.
     *
     * @param int   $id   ID of user to delete.
     * @param array $args Optional. Extra parameters to pass to WordPress.
     */
    public function deleteUser($id, $args = [])
    {
        $result = wp_delete_user($id, $args);

        if (! $result) {
            throw new UnexpectedValueException('WordPress API driver failed deleting user.');
        }
    }

    /**
     * Get a User's ID from their username.
     *
     * @param string $username The username of the user to get the ID of
     * @return int ID of the user.
     * @throws \UnexpectedValueException If provided data is invalid
     */
    public function getUserIdFromLogin($username)
    {
        $user = get_user_by('login', $username);
        if (! ( $user instanceof \WP_User )) {
            throw new UnexpectedValueException(sprintf('User "%s" not found', $username));
        }
        return (int) $user->ID;
    }

    /**
     * Start a database transaction.
     */
    public function startTransaction()
    {
        $this->wpdb->query('SET autocommit = 0;');
        $this->wpdb->query('START TRANSACTION;');
    }

    /**
     * End (rollback) a database transaction.
     */
    public function endTransaction()
    {
        $this->wpdb->query('ROLLBACK;');
    }


    /*
     * Internal helpers.
     */

    /**
     * Get information about a plugin.
     *
     * @param string $name
     * @return string Plugin filename and path.
     */
    protected function getPlugin($name)
    {
        foreach (get_plugins() as $file => $_) {
            // Logic taken from WP-CLI.
            if ($file === "{$name}.php" || ($name && $file === $name) || (dirname($file) === $name && $name !== '.')) {
                return $file;
            }
        }

        return '';
    }
}
