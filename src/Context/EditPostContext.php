<?php

namespace PaulGibbs\WordpressBehatExtension\Context;

use PaulGibbs\WordpressBehatExtension\PageObject\EditPostPage;
use Behat\Gherkin\Node\PyStringNode;

class EditPostContext extends RawWordpressContext
{
    public function __construct(EditPostPage $edit_post_page)
    {
        $this->edit_post_page = $edit_post_page;
    }

    /**
     * @Given /^I am on the edit ([a-zA-z_-]+) screen for "([^"]*)"$/
     */
    public function iGoToEditScreenForPostType($postType, $title)
    {
        $post = $this->getDriver()->getPostByName($title, $postType);
        $this->edit_post_page->open(array(
            'id' => $post->ID,
        ));
    }

    /**
     * @Given /^I am on the edit screen for "(?P<title>[^"]*)"$/
     */
    public function iGoToEditScreenFor($title)
    {
        $post = $this->getDriver()->getPostByName($title, null);
        $this->edit_post_page->open(array(
            'id' => $post->ID,
        ));
    }

    /**
     * @When /^I change the title to "(?P<title>[^"]*)"$/
     */
    public function iChangeTitleTo($title)
    {
        $this->edit_post_page->fillField('title', $title);
    }

    /**
     * @When I switch to the post content editor's :mode mode
     */
    public function iSelectPostContentEditorMode($mode)
    {
        $content_editor = $this->edit_post_page->getContentEditor();
        $content_editor->setMode(strtoupper($mode));
    }

    /**
     * @When I enter the following content into the post content editor:
     */
    public function iEnterContentIntoPostContentEditor(PyStringNode $content)
    {
        $content_editor = $this->edit_post_page->getContentEditor();
        $content_editor->setContent($content);
    }

    /**
     *
     * @Then the post content editor is in :mode mode
     */
    public function postContentEditorIsInMode($mode)
    {
        $content_editor = $this->edit_post_page->getContentEditor();
        if (strtoupper($mode) !== $content_editor->getMode()) {
            throw new ExpectationException(
                sprintf('Content editor is in "" mode. Expected "".', $content_editor->getMode(), $mode),
                $this->getDriver()
            );
        }
    }

    /**
     * @When /^I press the (publish|update) button$/
     */
    public function iPressThePublishButton()
    {
        //TODO wait if the button is disabled during auto-save
        $this->edit_post_page->pressButton('publish');
    }

    /**
     * @Then /^I should be on the edit "([a-zA-z_-]+)" screen for "([^"]*)"$/
     */
    public function iAmOnEditScreenForPostType($postType, $title)
    {
        $post = $this->getDriver()->getPostByName($title, $postType);
        $this->edit_post_page->isOpen(array(
            'id' => $post->ID,
        ));
    }

    /**
     * @Then /^I should be on the edit screen for "([^"]*)"$/
     */
    public function iAmOnEditScreenFor($title)
    {
        $post = $this->getDriver()->getPostByName($title, null);
        $this->edit_post_page->isOpen(array(
            'id' => $post->ID,
        ));
    }
}
