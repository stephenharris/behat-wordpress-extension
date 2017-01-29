<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use Behat\Gherkin\Node\TableNode;

/**
 * Provides step definitions for creating content: post types, comments, and terms.
 */
class ContentContext extends RawWordpressContext
{
    /**
     * Create content of the given type.
     *
     * Example: Given there are posts:
     *     | post_type | post_title | post_content | post_status |
     *     | page      | Tes Post   | Hello World  | publish     |
     *
     * @Given /^(?:there are|there is a) posts?:/
     *
     * @param TableNode $posts
     */
    public function thereArePosts(TableNode $posts)
    {
        foreach ($posts->getHash() as $post) {
            $this->createContent($post);
        }
    }

    /**
     * Create content, and go to it in the browser.
     *
     * Example: Given I am viewing a post:
     *     | post_type | post_title | post_content | post_status |
     *     | page      | Tes Post   | Hello World  | publish     |
     *
     * @Given /^(?:I am|they are) viewing a (?:blog) post:/
     *
     * @param TableNode $post_data
     */
    public function iAmViewingBlogPost(TableNode $post_data)
    {
        $post = $this->createContent($post_data->getHash());
        $this->visitPath(sprintf('?p=%d', (int) $post['id']));
    }
}
