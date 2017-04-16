<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Wpcli;

use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;
use Exception;
use function PaulGibbs\WordpressBehatExtension\Util\buildCLIArgs;

/**
 * WP-CLI driver element for post comments.
 */
class CommentElement extends BaseElement
{
    /**
     * Create an item for this element.
     *
     * @param array $args Data used to create an object.
     *
     * @return mixed The new item.
     */
    public function create($args)
    {
        $wpcli_args = buildCLIArgs(
            array(
                'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_date',
                'comment_date_gmt', 'comment_parent', 'comment_post_ID', 'user_id', 'comment_agent', 'comment_author_IP',
            ),
            $args
        );

        $wpcli_args = array_unshift($wpcli_args, '--porcelain');

        return (int) $this->drivers->getDriver()->wpcli('comment', 'create', $wpcli_args)['stdout'];
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
            ['force'],
            $args
        );

        $wpcli_args = array_unshift($wpcli_args, $id);

        $this->drivers->getDriver()->wpcli('comment', 'delete', $wpcli_args);
    }
}
