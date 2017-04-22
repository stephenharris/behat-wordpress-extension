<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Wpapi;

use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;
use UnexpectedValueException;

/**
 * WP-API driver element for content (i.e. blog posts).
 */
class ContentElement extends BaseElement
{
    /**
     * Create an item for this element.
     *
     * @param array $args Data used to create an object.
     *
     * @return \WP_Post The new item.
     */
    public function create($args)
    {
        $args = wp_slash($args);
        $post = wp_insert_post($args);

        if (is_wordpress_error($post)) {
            throw new UnexpectedValueException(sprintf('Failed creating new content: %s', $post->get_error_message()));
        }

        return $this->get($post->ID);
    }

    /**
     * Retrieve an item for this element.
     *
     * @param \WP_Post|int $id   Object ID.
     * @param array        $args Not used.
     *
     * @return \WP_Post The item.
     */
    public function get($id, $args = [])
    {
        $post = get_post($id);

        if (! $post) {
            throw new UnexpectedValueException(sprintf('Could not find content with ID %d', $id));
        }

        return $post;
    }

    /**
     * Delete an item for this element.
     *
     * @param int   $id   Object ID.
     * @param array $args Optional data used to delete an object.
     */
    public function delete($id, $args = [])
    {
        $result = wp_delete_post($id, isset($args['force']));

        if (! $result) {
            throw new UnexpectedValueException('Failed deleting content.');
        }
    }
}
