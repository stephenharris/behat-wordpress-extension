<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Wpcli;

use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;
use function PaulGibbs\WordpressBehatExtension\Util\buildCLIArgs;

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
        $wpcli_args = buildCLIArgs(
            array(
                'ID', 'user_pass', 'user_nicename', 'user_url', 'display_name', 'nickname', 'first_name', 'last_name',
                'description', 'rich_editing', 'comment_shortcuts', 'admin_color', 'use_ssl', 'user_registered',
                'show_admin_bar_front', 'role', 'locale',
            ),
            $args
        );

        $wpcli_args = array_unshift($wpcli_args, $args['user_login'], $args['user_email'], '--porcelain');

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
        $wpcli_args = buildCLIArgs(
            ['network', 'reassign'],
            $args
        );

        $wpcli_args = array_unshift($wpcli_args, $id, '--yes');

        $this->drivers->getDriver()->wpcli('user', 'delete', $wpcli_args);
    }
}
