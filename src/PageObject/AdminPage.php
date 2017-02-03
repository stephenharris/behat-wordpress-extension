<?php
namespace PaulGibbs\WordpressBehatExtension\PageObject;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class AdminPage extends Page
{

    /**
     * We use WordHat's site_url  property rather than Mink's base_url property
     * to get the correct URL to wp-admin, wp-login.php etc.
     * @param string $path
     * @return string Absolute URL
     */
    private function makeSurePathIsAbsolute($path)
    {
        $site_url = rtrim($this->getParameter('site_url'), '/').'/';
        return 0 !== strpos($path, 'http') ? $site_url.ltrim($path, '/') : $path;
    }

    public function getHeaderText()
    {
        $header = $this->getHeaderElement();
        $header_text = $header->getText();
        $header_link = $header->find('css', 'a');

        // The page headers can often incude an 'add new link'. Strip that out of the header text.
        if ($header_link) {
            $header_text  = trim(str_replace($header_link->getText(), '', $header_text));
        }

        return $header_text;
    }

    public function assertHasHeader($expected)
    {
        $actual = $this->getHeaderText();
        if ($expected !== $actual) {
            throw new \Exception(sprintf('Expected page header "%s", found "%s".', $expected, $actual));
        }
    }

    private function getHeaderElement()
    {
        // h2s were used prior to 4.3/4 and h1s after
        // @see https://make.wordpress.org/core/2015/10/28/headings-hierarchy-changes-in-the-admin-screens/
        $header2 = $this->find('css', '.wrap > h2');
        $header1 = $this->find('css', '.wrap > h1');

        if ($header1) {
            return $header1;
        } elseif ($header2) {
            return $header2;
        }

        throw new \Exception('Header could not be found');
    }

    public function clickLinkInHeader($link)
    {
        $header = $this->getHeaderElement();
        $header->clickLink($link);
    }

    public function getMenu()
    {
        return $this->getElement('Admin menu');
    }

    /**
     * Modified isOpen function which throws exceptions
     * @param array $url_parameters
     * @see https://github.com/sensiolabs/BehatPageObjectExtension/issues/57
     * @return boolean
     */
    public function isOpen(array $url_parameters = array())
    {
        $this->verify($url_parameters);
        return true;
    }
}
