<?php

namespace myproject\Tests;

use myproject\Curl\Curl;

/**
 * Represents api test such as Drupal or Varnish.
 */
abstract class HttpTest extends Test {

  abstract public function parseResultForUsername(string $result) : string;

  public function header(string $param) : string {
    $results = $this->run();

    return isset($results['headers'][$param]) ? $results['headers'][$param] : '';
  }

  /**
   * {@inheritdoc}
   */
  public function results() : array {
    $user_id = 5;
    $url = 'http://' . $this->server() . '/username/' . $user_id;

    $results = Curl::instance()->get($url);

    if ($results === FALSE) {
      throw new \Exception('Could not get information from ' . $this->name() . '; this might mean that user with ID ' . $user_id . ' does not exist.');
    }
    return [
      'real_username' => $this->parseResultForUsername($results['b']),
      'headers' => $results['h'],
      'body' => $results['b'],
    ];
  }

  abstract public function server() : string;

}
