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
     * Modified Page::isOpen() function which throws an exception on failure
     * @see https://github.com/sensiolabs/BehatPageObjectExtension/issues/57
     * @param array $url_parameters
     * @return boolean
     * @throws SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException
     *         If the current page does not match this one.
     */
    public function isOpen(array $url_parameters = array())
    {
        $this->verify($url_parameters);
        return true;
    }

    /**
     * Get the URL based on WordHat's site_url and not Mink's base_url
     *
     * We override this method as we need to modify the private method Page::makeSurePathIsAbsolute()
     *
     * @param array $url_parameters
     * @return string Absolute URL of this page
     */
    protected function getUrl(array $url_parameters = array())
    {
        return $this->makeSurePathIsAbsolute($this->unmaskUrl($url_parameters));
    }

    /**
     * We use WordHat's site_url  property rather than Mink's base_url property
     * to get the correct URL to wp-admin, wp-login.php etc.
     *
     * @param string $path Relative path of this page
     * @return string Absolute URL
     */
    private function makeSurePathIsAbsolute($path)
    {
        $site_url = rtrim($this->getParameter('site_url'), '/').'/';
        return 0 !== strpos($path, 'http') ? $site_url.ltrim($path, '/') : $path;
    }

    /**
     * Insert values for placeholders in the page's path
     *
     * @param array $url_parameters
     * @return string
     */
    private function unmaskUrl(array $url_parameters)
    {
        $url = $this->getPath();

        foreach ($url_parameters as $parameter => $value) {
            $url = str_replace(sprintf('{%s}', $parameter), $value, $url);
        }

        return $url;
    }
}
