<?php

namespace myproject\Tests;

/**
 * Represents api test such as Drupal or Varnish.
 */
abstract class ApiTest extends HttpTest {

  /**
   * {@inheritdoc}
   */
  public function parseResultForUsername(string $result) : string {
    $decoded = @json_decode($result);
    if (isset($decoded->name)) {
      return $decoded->name;
    }
    throw new \Exception('No username could be decoded; perhaps the user id does not exist.');
  }

}
