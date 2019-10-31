<?php

namespace myproject\Tests;

/**
 * Represents a client test.
 */
class Client extends HttpTest {

  public function cached() : bool {
    $results = $this->run();

    return strpos($results['body'], '**CACHED**') !== FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function name() : string {
    return 'Client';
  }

  /**
   * {@inheritdoc}
   */
  public function parseResultForUsername(string $result) : string {
    $matches = [];
    preg_match('/user 5 is (.*)</', $result, $matches);
    if (isset($matches[1])) {
      return $matches[1];
    }
    throw new \Exception('Could not parse ' . $result);
  }

  public function server() : string {
    return 'localhost';
  }

}
