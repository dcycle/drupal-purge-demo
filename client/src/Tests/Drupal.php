<?php

namespace myproject\Tests;

/**
 * Represents a drupal test.
 */
class Drupal extends ApiTest {

  public function cached() : bool {
    return $this->header('X-Drupal-Cache') === 'HIT';
  }

  /**
   * {@inheritdoc}
   */
  public function name() : string {
    return 'Drupal';
  }

  public function server() : string {
    return 'drupal';
  }

}
