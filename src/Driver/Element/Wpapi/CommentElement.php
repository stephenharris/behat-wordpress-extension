<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Wpapi;

use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;
use UnexpectedValueException;

/**
 * WP-API driver element for post comments.
 */
class CommentElement extends BaseElement
{
    /**
     * Create an item for this element.
     *
     * @param array $args Data used to create an object.
     *
     * @return \WP_Comment The new item.
     */
    public function create($args)
    {
        $comment_id = wp_new_comment($args);

        if (! $comment_id || is_wp_error($comment_id)) {
            throw new UnexpectedValueException(
                sprintf(
                    'Failed creating a new comment: %s',
                    $user->get_error_message()
                )
            );
        }

        return $this->get($comment_id);
    }

    /**
     * Retrieve an item for this element.
     *
     * @param int|string $id   Object ID.
     * @param array      $args Not used.
     *
     * @return \WP_Comment The item.
     */
    public function get($id, $args = [])
    {
        $comment = get_comment($id);

        if (! $comment) {
            throw new UnexpectedValueException(sprintf('Could not find comment with ID %d', $id));
        }

        return $comment;
    }

    /**
     * Delete an item for this element.
     *
     * @param int|string $id   Object ID.
     * @param array      $args Optional data used to delete an object.
     */
    public function delete($id, $args = [])
    {
        $result = wp_delete_comment($id, isset($args['force']));

        if (! $result) {
            throw new UnexpectedValueException('Failed deleting a comment.');
        }
    }
}
