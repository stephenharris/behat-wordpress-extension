<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Wpcli;

use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;

/**
 * WP-CLI driver element for plugins.
 */
class PluginElement extends BaseElement
{
    /**
     * Activate or deactivate specified plugin.
     *
     * @param string $id   Plugin name to affect.
     * @param array  $args Optional data used to update an object.
     */
    public function update($id, $args = [])
    {
        if (! isset($args['action']) || ! in_array($args['action'], ['activate', 'deactivate'], true)) {
            $args['action'] = 'activate';
        }

        $this->drivers->getDriver()->wpcli('plugin', $args['action'], [$id]);
    }


    /*
     * Convenience methods.
     */

    /**
     * Alias of update().
     *
     * @see update()
     *
     * @param string $id   Plugin name to activate.
     * @param array  $args Optional data used to update an object.
     */
    public function activate($id, $args = [])
    {
        $this->update($id, ['action' => 'activate']);
    }

    /**
     * Alias of update().
     *
     * @see update()
     *
     * @param string $id   Plugin name to deactivate.
     * @param array  $args Optional data used to update an object.
     */
    public function deactivate($id, $args = [])
    {
        $this->update($id, ['action' => 'deactivate']);
    }
}
