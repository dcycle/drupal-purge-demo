<?php

namespace myproject\Tests;

/**
 * Represents a varnish test.
 */
class Varnish extends ApiTest {

  public function cached() : bool {
    return $this->header('Age') > 0;
  }

  /**
   * {@inheritdoc}
   */
  public function name() : string {
    return 'Varnish';
  }

  public function server() : string {
    return 'varnish';
  }

}
