<?php

namespace PaulGibbs\WordpressBehatExtension\PageObject\Element;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;
use Behat\Mink\Exception\UnsupportedDriverActionException;

class AdminMenu extends Element
{
    /**
     * @var array|string $selector
     */
    protected $selector = '#adminmenu';

    /**
     * @return array Array of top-level menu item names (i.e. [ "Dashboard", "Posts", ...])
     */
    public function getTopLevelMenuItems()
    {
        $menuItem_nodes = $this->findAll('css', '#adminmenu > li a .wp-menu-name');
        $menuItem_texts = array();

        foreach ($menuItem_nodes as $n => $element) {
            $menuItem_texts[] = $this->stripTagsAndContent($element->getHtml());
        }

        return $menuItem_texts;
    }

    /**
     * Clicks a menu item, given its human-readable path, delimited by >
     * @param string $item Of the form "Plugins" or "Plugins > Add New"
     * @throws \Exception If the menu item does not exist
     */
    public function clickMenuItem($item)
    {
        $item = array_map('trim', preg_split('/(?<!\\\\)>/', $item));
        $click_node = false;

        $first_level_items = $this->findAll('css', 'li.menu-top');

        foreach ($first_level_items as $first_level_item) {
            // We use getHtml and strip the tags, as `.wp-menu-name` might not be visible (i.e. when the menu is
            // collapsed) so getText() will be empty.
            // @link https://github.com/stephenharris/WordPressBehatExtension/issues/2
            $item_name = $this->stripTagsAndContent($first_level_item->find('css', '.wp-menu-name')->getHtml());

            if (strtolower($item[0]) == strtolower($item_name)) {
                if (isset($item[1])) {
                    $second_level_items = $first_level_item->findAll('css', 'ul li a');

                    foreach ($second_level_items as $second_level_item) {
                        $item_name = $this->stripTagsAndContent($second_level_item->getHtml());
                        if (strtolower($item[1]) == strtolower($item_name)) {
                            try {
                                // Focus on the menu link so the submenu appears
                                $first_level_item->find('css', 'a.menu-top')->focus();
                            } catch (UnsupportedDriverActionException $e) {
                                // This will fail for GoutteDriver but neither is it necessary
                            }
                            $click_node = $second_level_item;
                            break;
                        }
                    }
                } else {
                    // We are clicking a top-level item:
                    $click_node = $first_level_item->find('css', 'a');
                }
                break;
            }
        }

        if (false === $click_node) {
            throw new \Exception('Menu item could not be found');
        }

        $click_node->click();
    }

    /**
     * Extracts 'top level' text from HTML.
     *
     * All HTML tags, and their contents are removed.
     *
     * e.g. Some <span>HTML and</span>text  -->  Some text
     *
     * @param string $html Raw HTML
     * @param string Extracted text. e.g. Some <span>HTML and</span>text  -->  Some text
     */
    private function stripTagsAndContent($html)
    {
        if (trim($html) == '') {
            return $html;
        }

        $doc = new \DOMDocument();
        $doc->loadHTML("<div>{$html}</div>");

        $container = $doc->getElementsByTagName('div')->item(0);

        // Remove nodes while iterating over them does not work
        // @link http://php.net/manual/en/domnode.removechild.php#90292
        $remove_queue = array();
        foreach ($container->childNodes as $child_node) {
            if ($child_node->nodeType !== XML_TEXT_NODE) {
                $remove_queue[] = $child_node;
            }
        }

        foreach ($remove_queue as $node) {
            $container->removeChild($node);
        }

        return trim($container->textContent);
    }
}
