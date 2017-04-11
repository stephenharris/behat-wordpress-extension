<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Wpcli;

use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;
use Exception;
use function PaulGibbs\WordpressBehatExtension\Util\buildCLIArgs;

/**
 * WP-CLI driver element for content (i.e. blog posts).
 */
class ContentElement extends BaseElement
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
                'ID', 'post_author', 'post_date', 'post_date_gmt', 'post_content', 'post_content_filtered', 'post_title',
                'post_excerpt', 'post_status', 'post_type', 'comment_status', 'ping_status', 'post_password', 'post_name',
                'to_ping', 'pinged', 'post_modified', 'post_modified_gmt', 'post_parent', 'menu_order', 'post_mime_type',
                'guid', 'post_category', 'tax_input', 'meta_input',
            ),
            $args
        );

        $wpcli_args = array_unshift($wpcli_args, '--porcelain');

        return (int) $this->drivers->getDriver()->wpcli('post', 'create', $wpcli_args)['stdout'];
    }

    /**
     * Retrieve an item for this element.
     *
     * @param int|string $id   Object ID.
     * @param array      $args Optional data used to fetch an object.
     *
     * @return mixed The item.
     */
    public function get($id, $args = [])
    {
        $wpcli_args = [
            '--format'    => 'json',
            '--post__in'  => $id,
            '--post_type' => 'any',
            '--fields'    => 'ID,post_name,url',
        ];

        $post = $this->drivers->getDriver()->wpcli('post', 'get', $wpcli_args)['stdout'];
        $post = json_decode($post);

        if (! $post) {
            throw new Exception(sprintf('Could not find post with ID %d', $id));
        }

        return array(
            'id'   => $post->ID,
            'slug' => $post->post_name,
            'url'  => $post->url,
        );
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
            ['force', 'defer-term-counting'],
            $args
        );

        $wpcli_args = array_unshift($wpcli_args, $id);

        $this->drivers->getDriver()->wpcli('post', 'delete', $wpcli_args);
    }
}
