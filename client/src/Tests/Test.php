<?php

namespace myproject\Tests;

use myproject\Templatable\Templatable;

/**
 * Represents a basic test.
 */
abstract class Test extends Templatable {

  /**
   * Constructor.
   *
   * @param string $req_username
   *   Required username, if possible.
   */
  public function __construct(string $req_username = '') {
    $this->req_username = $req_username;
  }

  abstract public function cached() : bool;

  public function cachedHuman() : string {
    return $this->cached() ? 'Yes' : 'No';
  }

  public function color() : string {
    if ($euser = $this->req_username) {
      $ruser = $this->username();
      if ($ruser != $euser) {
        return 'red';
      }
    }
    return $this->cached() ? 'green' : 'orange';
  }

  /**
   * {@inheritdoc}
   */
  public function display(array $variables = []) : string {
    $results = $this->run();

    return parent::display([
      '__TEST_FOR__' => $this->name(),
      '__DURATION__' => $results['duration'],
      '__RUSER__' => $this->username(),
      '__EUSER__' => $this->req_username ? : '(any username)',
      '__COLOR__' => $this->color(),
      '__CACHE__' => $this->cachedHuman(),
    ]);
  }

  /**
   * The display name for this test.
   *
   * @return string
   *   A display name.
   */
  abstract public function name() : string;

  /**
   * Run the test and return results; do not use cache.
   *
   * @return array
   *   The results.
   */
  abstract public function results() : array;

  /**
   * Run the test and return results; use cache if possible.
   *
   * @return array
   *   The results.
   */
  public function run() : array {
    static $results;

    if (!$results) {
      $sleep = 0;
      sleep($sleep);
      $start = microtime();
      $results = $this->results();
      $results = array_merge($results, [
        'duration' => max(0, microtime() - $start),
      ]);
    }

    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function templateName() : string {
    return 'templates/test.html';
  }

  /**
   * The actual username.
   *
   * @return string
   *   The username.
   */
  public function username() : string {
    $results = $this->run();
    if (!array_key_exists('real_username', $results)) {
      throw new \Exceptin('results[real_username] does not exist');
    }
    if (!$results['real_username']) {
      throw new \Exceptin('results[real_username] is empty');
    }
    return $results['real_username'];
  }

}
