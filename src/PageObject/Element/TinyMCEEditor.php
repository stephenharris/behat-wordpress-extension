<?php
namespace PaulGibbs\WordpressBehatExtension\PageObject\Element;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

/**
 * An Element representing a WYSIWYG editor
 */
class TinyMCEEditor extends Element
{

    /**
     * The avaible modes for the WYSIWYG editor
     */
    const VISUAL = 'VISUAL';
    const TEXT = 'TEXT';

    /**
     * @var $wysiwyg_iframe_id The element ID of the underlying iFrame
     */
    protected static $wysiwyg_iframe_id = 'content_ifr';

    /**
     * @var $textarea_id The element ID of the underlying textarea
     */
    protected static $textarea_id = 'content';

    /**
     * Sets the mode of the WYSIWYG editor
     * @param string $mode VISUAL or TEXT
     */
    public function setMode($mode)
    {
        if (self::VISUAL === $mode) {
            $this->find('css', '#content-tmce')->press();
        } else {
            $this->find('css', '#content-html')->press();
        }
    }

    /**
     * Returns the mode of the WYSIWYG editor
     * @return string 'VISUAL' or 'TEXT'
     */
    public function getMode()
    {
        return $this->find('css', '#wp-content-wrap')->hasClass('tmce-active') ? self::VISUAL : self::TEXT;
    }

    /**
     * Enter the given content into the WYSIWYG editor
     * @param string $content The content being entered
     */
    public function setContent($content)
    {
        if (self::VISUAL === $this->getMode()) {
            $this->getDriver()->switchToIFrame(self::$wysiwyg_iframe_id);
            $this->getDriver()->executeScript(
                ';document.body.innerHTML = \'<p>' . addslashes(htmlspecialchars($content)) . '</p>\';'
            );
            $this->getDriver()->switchToIFrame();
        } else {
            $this->fillField(self::$textarea_id, $content);
        }
    }
}
