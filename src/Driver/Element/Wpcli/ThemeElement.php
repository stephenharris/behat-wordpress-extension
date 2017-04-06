<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Wpcli;

use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;

/**
 * WP-CLI driver element for themes.
 */
class ThemeElement extends BaseElement
{
    /**
     * Switch active theme.
     *
     * @param string $id   Theme name to switch to.
     * @param array  $args Not used.
     */
    public function update($id, $args = [])
    {
        $this->drivers->getDriver()->wpcli('theme', 'activate', [$id]);
    }


    /*
     * Convenience methods.
     */

    /**
     * Alias of update().
     *
     * @see update()
     *
     * @param string $id   Theme name to switch to.
     * @param array  $args Not used.
     *
     * @return string Path to the export file.
     */
    public function change($id, $args = [])
    {
        return $this->update($id, $args);
    }
}
