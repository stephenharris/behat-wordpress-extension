<?php
namespace PaulGibbs\WordpressBehatExtension\PageObject;

/**
 * Page object representing the Dashboard page.
 */
class EditPostPage extends AdminPage
{

    protected $path = '/wp-admin/post.php?post={id}&action=edit';
    /**
     * @param array $urlParameters
     */
    protected function verifyPage()
    {
        $this->assertHasHeader('Edit Post');
    }

    public function getContentEditor()
    {
        return $this->getElement('Content editor');
    }
}
