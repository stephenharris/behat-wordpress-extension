<?php
namespace PaulGibbs\WordpressBehatExtension\PageObject\Element;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;
use Behat\Mink\Exception\UnsupportedDriverActionException;

/**
 * An Element representing the admin menu.
 */
class ContentEdtior extends Element
{

    const VISUAL = 'VISUAL';
    const TEXT = 'TEXT';

    public function setMode($mode)
    {
        if (self::VISUAL === $mode) {
            $this->find('css', '#content-tmce')->pressButton();
        } else {
            $this->find('css', '#content-html')->pressButton();
        }
    }

    public function getMode()
    {
        return $this->find('css', '#wp-content-wrap')->hasClass('tmce-active') ? self::VISUAL : self::TEXT;
    }
}
