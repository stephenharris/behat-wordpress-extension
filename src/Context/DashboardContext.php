<?php
namespace PaulGibbs\WordpressBehatExtension\Context;

use PaulGibbs\WordpressBehatExtension\PageObject\AdminPage;
use PaulGibbs\WordpressBehatExtension\PageObject\Dashboard;

/**
 * Provides step definitions that are specific to the WordPress dashboard (wp-admin).
 */
class DashboardContext extends RawWordpressContext
{

    protected $admin_page;
    protected $dashboard;

    public function __construct(AdminPage $admin_page, Dashboard $dashboard)
    {
        $this->admin_page = $admin_page;
        $this->dashboard = $dashboard;
    }

    /**
     * @When I click on the :link link in the header
     */
    public function iClickOnHeaderLink($link)
    {
        $this->admin_page->clickLinkInHeader($link);
    }

    /**
     * @Then I should be on the :admin_page page
     */
    public function iShouldBeOnThePage($admin_page)
    {
        $this->admin_page->assertHasHeader($admin_page);
    }

    /**
     * @Given I am on the Dashboard
     */
    public function iAmOnDashboard()
    {
        $this->dashboard->open();
    }

    /**
     * @Given I go to menu item :item
     */
    public function iGoToMenuItem($item)
    {
        $adminMenu = $this->admin_page->getMenu();
        $adminMenu->clickMenuItem($item);
    }

    /**
     * Check the specified notification is on-screen.
     *
     * Example: Then I should see a status message that says "Post published"
     *
     * @Then /^(?:I|they) should see an? (error|status) message that says "([^"]+)"$/
     *
     * @param string $type    Message type. Either "error" or "status".
     * @param string $message Text to search for.
     */
    public function iShouldSeeMessageThatSays($type, $message)
    {
        $selector = 'div.notice';

        if ($type === 'error') {
            $selector .= '.error';
        } else {
            $selector .= '.updated';
        }

        $this->assertSession()->elementTextContains('css', $selector, $message);
    }
}
