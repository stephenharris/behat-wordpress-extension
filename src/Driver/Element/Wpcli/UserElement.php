<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Wpcli;

use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;

/**
 * WP-CLI driver element for managing user accounts.
 */
class UserElement extends BaseElement
{
    /**
     * Create an item for this element.
     *
     * @param array $args Data used to create an object.
     * @return int New object ID.
     */
    public function create($args)
    {
        $wpcli_args = [$args['user_login'], $args['user_email'], '--porcelain'];
        $whitelist  = array(
            'ID', 'user_pass', 'user_nicename', 'user_url', 'display_name', 'nickname', 'first_name', 'last_name',
            'description', 'rich_editing', 'comment_shortcuts', 'admin_color', 'use_ssl', 'user_registered',
            'show_admin_bar_front', 'role', 'locale',
        );

        foreach ($whitelist as $option) {
            if (isset($args[$option])) {
                $wpcli_args["--{$option}"] = $args[$option];
            }
        }

        return (int) $this->drivers->getDriver()->wpcli('user', 'create', $wpcli_args)['stdout'];
    }

    /**
     * Delete an item for this element.
     *
     * @param int|string $id   Object ID.
     * @param array      $args Optional data used to delete an object.
     */
    public function delete($id, $args = [])
    {
        $wpcli_args = [$id, '--yes'];
        $whitelist  = ['network', 'reassign'];

        foreach ($whitelist as $option => $value) {
            // TODO: review why this whitelisting is different from all the others.
            if (isset($args[$option])) {
                if (is_int($option)) {
                    $wpcli_args[] = "--{$value}";
                } else {
                    $wpcli_args[] = sprintf('%s=%s', $option, escapeshellarg($value));
                }
            }
        }

        $this->drivers->getDriver()->wpcli('user', 'delete', $wpcli_args);
    }
}
