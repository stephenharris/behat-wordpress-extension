<?php
use PaulGibbs\WordpressBehatExtension\Context\RawWordpressContext;

use Behat\Behat\Context\SnippetAcceptingContext;

/**
 * Define application features from the specific context.
 */
class FeatureContext extends RawWordpressContext implements SnippetAcceptingContext {

    /**
     * Location to store screenshots, or false if none are to be taken
     * @var string|bool
     */
    protected $screenshot_dir = false;

    public function __construct($screenshot_dir=false) {
        if ( $screenshot_dir ) {
            $this->screenshot_dir = rtrim( $screenshot_dir, '/' ) . '/';
        }
        parent::__construct();
    }

    /**
     * @AfterScenario
     */
    public function takeScreenshotAfterFailedStep(AfterScenarioScope $scope)
    {
        if ($this->screenshot_dir) {
            $feature  = $scope->getFeature();
            $scenario = $scope->getScenario();
            $filename = basename( $feature->getFile(), '.feature' ) . '-' . $scenario->getLine();
            if ($this->getSession()->getDriver() instanceof \Behat\Mink\Driver\Selenium2Driver) {
                $screenshot = $this->getSession()->getDriver()->getScreenshot();
                file_put_contents( $this->screenshot_dir . $filename . '.png', $screenshot);
            }
            //Store HTML markup of the page also - useful for non-js tests
            file_put_contents( $this->screenshot_dir . $filename . '.html', $this->getSession()->getPage()->getHtml());
        }
    }
}
