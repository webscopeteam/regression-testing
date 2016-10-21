<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Drupal\gtg_follow\Entity\GtgFollowEntity;
use Behat\Mink\Exception\DriverException;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
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
   * Remove terms that we probably created. Nodes
   * are handled because when a user is deleted their content
   * is deleted as well. This not true for terms
   * that they create though.
   *
   * @AfterScenario
   */
  public function cleanupTerms($event) {
    /** @var Behat\Gherkin\Node\FeatureNode */
    $feature_title = $event->getFeature()->getTitle();
    if($feature_title == 'Follow a practice') {
      $query = \Drupal::entityQuery('gtg_follow_entity');
      $query->condition('status', 1);
      $query->condition('type', 'default_follow');
      $query->condition('name', 'Follow Test practice title', '=');
      $entity_ids = $query->execute();
      foreach ($entity_ids as $entity_id) {
        $follow_entity = GtgFollowEntity::load($entity_id);
        $follow_entity->delete();
      }
    }
  }



}
