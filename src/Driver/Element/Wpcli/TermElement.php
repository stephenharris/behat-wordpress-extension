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
        $wpcli_args = [
            $args['taxonomy'],
            $args['term'],
            '--porcelain',
        ];

        return (int) $this->drivers->getDriver()->wpcli('term', 'create', $wpcli_args)['stdout'];
    }

    /**
     * Delete an item for this element.
     *
     * @param int|string $id   Object ID.
     * @param array      $args Optional data used to delete an object.
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
