<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Wpcli;

use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;
use Exception;

/**
 * WP-CLI driver element for taxonomy terms.
 */
class TermElement extends BaseElement
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
                'description',
                'parent',
                'slug',
            ),
            $args
        );

        $wpcli_args = array_unshift($wpcli_args, $args['taxonomy'], $args['term'], '--porcelain');
        $term_id    = (int) $this->drivers->getDriver()->wpcli('term', 'create', $wpcli_args)['stdout'];

        return $this->get($term_id);
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
        $wpcli_args = buildCLIArgs(
            array(
                'field',
                'fields',
            ),
            $args
        );

        $wpcli_args = array_unshift($wpcli_args, $args['taxonomy'], $id, '--format=json');
        $term       = $this->drivers->getDriver()->wpcli('term', 'get', $wpcli_args)['stdout'];
        $term       = json_decode($term);

        if (! $term) {
            throw new Exception(sprintf('Could not find term with ID %d', $id));
        }

        return $term;
    }

    /**
     * Delete an item for this element.
     *
     * @param int|string $id   Object ID.
     * @param array      $args Unused.
     */
    public function delete($id, $args = [])
    {
        $wpcli_args = [
            $args['taxonomy'],
            $id,
        ];

        $this->drivers->getDriver()->wpcli('term', 'delete', [$wpcli_args]);
    }
}
