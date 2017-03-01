<?php
namespace PaulGibbs\WordpressBehatExtension\PageObject\Element;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

/**
 * An Element representing the admin menu.
 */
class ContentEditor extends Element
{

    protected $selector = '#postdivrich';

    const VISUAL = 'VISUAL';
    const TEXT = 'TEXT';

    protected static $wysiwyg_iframe_id = 'content_ifr';
    protected static $textarea_id = 'content';

    public function setMode($mode)
    {
        if (self::VISUAL === $mode) {
            $this->find('css', '#content-tmce')->press();
        } else {
            $this->find('css', '#content-html')->press();
        }
    }

    public function getMode()
    {
        return $this->find('css', '#wp-content-wrap')->hasClass('tmce-active') ? self::VISUAL : self::TEXT;
    }

    public function setContent($content)
    {
        if (self::VISUAL == $this->getMode()) {
            $this->getDriver()->switchToIFrame(self::$wysiwyg_iframe_id);
            $this->getDriver()->executeScript(";document.body.innerHTML = '<p>" . addslashes(htmlspecialchars($content)) . "</p>';");
            $this->getDriver()->switchToIFrame();
        } else {
            $this->fillField(self::$textarea_id, $content);
        }
    }
}
