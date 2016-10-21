<?php

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Context\TranslatableContext;
use Behat\Mink\Driver\Selenium2Driver;

/**
 * Defines application features from the specific context.
 */
class WebscopeMinkContext extends RawMinkContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given /^I set browser window size to "([^"]*)" x "([^"]*)"$/
     */
    public function iSetBrowserWindowSizeToX($width, $height) {
        $this->getSession()->resizeWindow((int)$width, (int)$height, 'current');
    }

    /**
     * Checks, that form field with specified id|name|label|value is not empty
     * Example: Then the "username" field should not be empty
     *
     * @Then /^the "(?P<field>(?:[^"]|\\")*)" field should not be empty$/
     */
    public function assertFieldNotEmpty($field)
    {
        $field = $this->fixStepArgument($field);
        $this->assertSession()->fieldValueNotEquals($field, '');
    }

    /**
     * Returns fixed step argument (with \\" replaced back to ")
     *
     * @param string $argument
     *
     * @return string
     */
    protected function fixStepArgument($argument)
    {
        return str_replace('\\"', '"', $argument);
    }

    /**
     * @param $seconds
     * @Then I wait :seconds seconds
     */
    public function i_wait_seconds($seconds)
    {
        $this->getSession()->wait($seconds * 1000, false);
    }

    /**
     * Create a screenshot or save the html.
     *
     * @param string $file_name
     *   The filename of the screenshot (complete).
     * @param string $message
     *   The message to be printed. @file_name will be replaced with $file name.
     * @param bool|true $ext
     *   Whether to add .png or .html to the name of the screenshot.
     */
    public function createScreenshot($file_name, $message, $ext = TRUE) {
        /** @var Behat\Mink\Driver\Selenium2Driver */
        if ($this->getSession()->getDriver() instanceof Selenium2Driver) {
            if ($ext) {
                $file_name .= '.png';
            }
            $screenshot = $this->getSession()->getDriver()->getScreenshot();
            file_put_contents($file_name, $screenshot);
        }
        else {
            if ($ext) {
                $file_name .= '.html';
            }
            $html_data = $this->getSession()->getDriver()->getContent();
            file_put_contents($file_name, $html_data);
        }
        if ($message) {
            print strtr($message, ['@file_name' => $file_name]);
        }
    }

    /**
     * Save screenshot.
     *
     * @Then (I )take a screenshot
     */
    public function takeScreenshotUnnamed() {
        $file_name = drupal_realpath('public://') . DIRECTORY_SEPARATOR . 'behat-screenshot';
        $message = "Screenshot created in @file_name";
        $this->createScreenshot($file_name, $message);
    }

    /**
     * @Then I take a regression screenshot named :name
     */
    public function iTakeARegressionScreenshotNamed($name)
    {
      $file_name = __DIR__. '/../screenshots/' . $name;
      $message = "Screenshot created in @file_name";
      $this->createScreenshot($file_name, $message);
    }

    /**
     * @Given I create a :content_type base line screenshot if required
     */
    public function iCreateABaseLineScreenshotIfRequired($content_type)
    {
        $file_name = __DIR__. '/../screenshots/' . $content_type . '.png';
        $test = file_get_contents($file_name);
        var_dump($test);
    }


}
