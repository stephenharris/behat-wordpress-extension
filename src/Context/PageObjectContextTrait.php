<?php

namespace PaulGibbs\WordpressBehatExtension\Context;

use Behat\Behat\Context\Context;
use SensioLabs\Behat\PageObjectExtension\PageObject\Factory as PageObjectFactory;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;
use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

trait PageObjectContextTrait
{
    /**
     * @var PageObjectFactory
     */
    private $pageObjectFactory = null;

    /**
         * Creates a page object from its name
     * @param string $name The name of the page object e.g 'Admin page'
     * @return Page
     * @throws \RuntimeException
     */
    public function getPage($name)
    {
        if (null === $this->pageObjectFactory) {
            throw new \RuntimeException('To create pages you need to pass a factory with setPageObjectFactory()');
        }

        return $this->pageObjectFactory->createPage($name);
    }

    /**
         * Creates a page object element from its name
     * @param string $name The name of the page object element e.g 'Toolbar'
     * @return Element
     * @throws \RuntimeException
     */
    public function getElement($name)
    {
        if (null === $this->pageObjectFactory) {
            throw new \RuntimeException('To create elements you need to pass a factory with setPageObjectFactory()');
        }

        return $this->pageObjectFactory->createElement($name);
    }

    /**
         * Sets the factory for creating page and element objects
     * @param PageObjectFactory $pageObjectFactory
     */
    public function setPageObjectFactory(PageObjectFactory $pageObjectFactory)
    {
        $this->pageObjectFactory = $pageObjectFactory;
    }

    /**
     * Returns the factory used for creating page and element objects
     * @return PageObjectFactory
     */
    public function getPageObjectFactory()
    {
        if (null === $this->pageObjectFactory) {
            throw new \RuntimeException(
                'To access the page factory you need to pass it first with setPageObjectFactory()'#
            );
        }

        return $this->pageObjectFactory;
    }
}
