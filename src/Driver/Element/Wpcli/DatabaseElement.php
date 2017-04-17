<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Wpcli;

use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;

/**
 * WP-CLI driver element for manipulating the database directly.
 */
class DatabaseElement extends BaseElement
{
    /**
     * Export site database.
     *
     * @param int   $id   Not used.
     * @param array $args Not used.
     *
     * @return string Path to the export file.
     */
    public function get($id, $args = [])
    {
        while (true) {
            $filename = uniqid('database-', true) . '.sql';
            if (! file_exists(getcwd() . "/{$filename}")) {
                break;
            }
        }

        // Protect against WP-CLI changing the filename.
        $filename = $this->drivers->getDriver()->wpcli('db', 'export', [$filename, '--porcelain'])['stdout'];

        return getcwd() . "/{$filename}";
    }

    /**
     * Import site database.
     *
     * If $id begins with a directory separator or ~ it is treated as an absolute path.
     * Otherwise, it is treated as relative to the current working directory.
     *
     * @param string $id   Relative or absolute path and filename of SQL file to import.
     * @param array  $args Not used.
     */
    public function update($id, $args = [])
    {
        if (! in_array($id[0], [DIRECTORY_SEPARATOR, '~'], true)) {
            $id = getcwd() . "/{$id}";
        }

        $this->drivers->getDriver()->wpcli('db', 'import', [$id]);
    }


    /*
     * Convenience methods.
     */

    /**
     * Alias of get().
     *
     * @see get()
     *
     * @param int   $id   Not used.
     * @param array $args Not used.
     *
     * @return string Path to the export file.
     */
    public function export($id, $args = [])
    {
        return $this->get($id, $args);
    }

    /**
     * Alias of update().
     *
     * @see update()
     *
     * @param string $id   Relative or absolute path and filename of SQL file to import.
     * @param array  $args Not used.
     */
    public function import($id, $args = [])
    {
        $this->update($id, $args);
    }
}
