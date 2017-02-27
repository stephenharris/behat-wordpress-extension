<?php
namespace PaulGibbs\WordpressBehatExtension\PageObject\Element;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

/**
 * An Element representing the admin menu.
 */
class ContentEdtior extends Element
{

    protected $selector = '#postdivrich';

    const VISUAL = 'VISUAL';
    const TEXT = 'TEXT';

    protected $wysiwyg_iframe_id = 'content_ifr';
    protected $textarea_id = 'content';

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

    public function setContent($content)
    {
        if (self::VISUAL == $this->getMode()) {
            $iframe = $this->find('css', "#" . self::$wysiwyg_iframe_id);
            $this->getDriver()->switchToIFrame($iframe);
            $this->getDriver()->executeScript("document.body.innerHTML = '<p>" . $content . "</p>'");
            $this->getDriver()->switchToIFrame();
        } else {
            $this->fillField('#' . self::$textarea_id);
        }
    }
}
