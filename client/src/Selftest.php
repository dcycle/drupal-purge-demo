<?php

namespace myproject;

use myproject\traits\Singleton;
use myproject\Templatable\Templatable;
use myproject\Tests\Database;
use myproject\Tests\Drupal;
use myproject\Tests\Varnish;
use myproject\Tests\Client;

/**
 * Encapsulated code for the application.
 */
class Selftest extends Templatable {

  use Singleton;

  /**
   * Return a "self-test" page.
   *
   * @return string
   *   The markup to display.
   */
  public function getPage() : string {
    $database = new Database();
    $username = $database->username();

    return $this->display([
      '__DATABASE__' => $database->display(),
      '__DRUPAL__' => (new Drupal($username))->display(),
      '__VARNISH__' => (new Varnish($username))->display(),
      '__SELF__' => (new Client($username))->display(),
    ]);
  }

  public function templateName() : string {
    return 'templates/selftest.html';
  }

}
