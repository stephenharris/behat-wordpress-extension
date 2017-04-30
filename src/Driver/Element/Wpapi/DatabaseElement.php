<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Wpapi;

use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;

/**
 * WP-API driver element for manipulating the database directly.
 */
class DatabaseElement extends BaseElement
{
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

    /**
     * Start a database transaction.
     */
    public function startTransaction()
    {
        $this->drivers->getDriver()->wpdb->query('SET autocommit = 0;');
        $this->drivers->getDriver()->wpdb->query('START TRANSACTION;');
    }

    /**
     * End (rollback) a database transaction.
     */
    public function endTransaction()
    {
        $this->drivers->getDriver()->wpdb->query('ROLLBACK;');
    }
}
