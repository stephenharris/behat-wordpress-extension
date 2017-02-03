<?php
namespace PaulGibbs\WordpressBehatExtension\PageObject;

class Dashboard extends AdminPage
{

    /**
     * @var string $path
     */
    protected $path = '/wp-admin/';

    /**
     * @param array $urlParameters
     */
    protected function verifyPage()
    {
        $this->assertHasHeader('Dashboard');
    }
}
