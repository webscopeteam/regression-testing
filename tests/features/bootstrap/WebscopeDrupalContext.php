<?php


use Behat\Gherkin\Node\TableNode;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Drupal\DrupalExtension\Hook\Scope\EntityScope;


class WebscopeDrupalContext extends RawDrupalContext {

  /**
   * Initializes context.
   *
   * Every scenario gets its own context instance.
   * You can also pass arbitrary arguments to the
   * context constructor through behat.yml.
   */
  public function __construct() {
  }

  /**
   * Creates content of the given type, provided in the form:
   * | title     | My node        |
   * | Field One | My field value |
   * | author    | Joe Editor     |
   * | status    | 1              |
   * | ...       | ...            |
   *
   * @Given I am editing a/an :type( content):
   */
  public function assertEditingNode($type, TableNode $fields) {
    $node = (object) array(
      'type' => $type,
    );
    foreach ($fields->getRowsHash() as $field => $value) {
      $node->{$field} = $value;
    }

    $saved = $this->nodeCreate($node);

    // Set internal browser on the node.
    $this->getSession()
      ->visit($this->locatePath('/node/' . $saved->nid . '/edit'));
  }

  /**
   * @Then I should see the :arg1 element with the :arg2 attribute set to :arg3
   */
  public function iShouldSeeTheElementWithTheAttributeSetTo($tag, $attribute, $value) {
    $session = $this->getSession();
    $element = $session->getPage()->find('css', $tag);
    if (empty($element)) {
      throw new \Exception(sprintf('The element "%s" was not found on the page %s', $tag, $this->getSession()
        ->getCurrentUrl()));
    }
    if (!empty($attribute)) {
      $found = FALSE;
      $attr = $element->getAttribute($attribute);
      if (!empty($attr)) {
        $found = TRUE;
        if (strpos($attr, "$value") === FALSE) {
          throw new \Exception(sprintf('The "%s" attribute does not equal "%s" on the element "%s"  on the page %s', $attribute, $value, $tag, $this->getSession()
            ->getCurrentUrl()));
        }
      }
      if (!$found) {
        throw new \Exception(sprintf('The "%s" attribute is not present on the element "%s" on the page %s', $attribute, $tag, $this->getSession()
          ->getCurrentUrl()));
      }
    }
  }


}
