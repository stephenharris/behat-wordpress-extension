<?php
namespace PaulGibbs\WordpressBehatExtension;

/**
 * Is the specified item's class a WordPress error object?
 *
 * @param object $item
 * @return bool
 */
function is_wordpress_error($item)
{
    return (is_object($item) && get_class($item) === 'WP_Error');
}
