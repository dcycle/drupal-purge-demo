<?php

namespace myproject;

use myproject\Templatable\Templatable;
use myproject\traits\Singleton;

/**
 * Encapsulated code for the application.
 */
class App extends Templatable {

  use Singleton;

  /**
   * Clear cached files.
   *
   * @return string
   *   The markup to display.
   *
   * @throws \Exception
   */
  public function clearcache() {
    try {
      $this->rrmdir('/var/www/html/username/');
      return 'Caches cleared.';
    }
    catch (\Throwable $t) {
      return "Got an error when trying to clear cache: " . $t->getMessage();
    }
  }

  /**
   * Generate a cached page and return its contents.
   *
   * @return string
   *   The markup to display.
   *
   * @throws \Exception
   */
  public function generateCachedPage() {
    $id = $this->requestedUserId();
    return $this->generateCachedPageWithUsername($id, $this->usernameFromApi($id));
  }

  /**
   * Generate a cached page and return its contents.
   *
   * @param int $user_id
   *   A user id.
   * @param string $username
   *   A username to display.
   *
   * @return string
   *   The markup to display.
   *
   * @throws \Exception
   */
  public function generateCachedPageWithUsername(int $user_id, string $username) : string {
    $contents = $this->display([
      '__USER_ID__' => $user_id,
      '__USERNAME__' => $username,
      '__CACHE_TIME__' => date('Y-m-d H:i:s'),
      '__CACHED__' => '**UNCACHED**',
    ]);

    @mkdir('/var/www/html/username/');
    file_put_contents('/var/www/html/username/' . $user_id, str_replace('**UNCACHED**', '**CACHED**', $contents));

    return $contents;
  }

  /**
   * Prints the page, and saves a cached version for future use.
   *
   * @return string
   *   The markup to display.
   */
  public function getPage() : string {
    // If we are here, it is because /username/x does not exist in the cache.
    // Let's create it.
    try {
      return $this->generateCachedPage();
    }
    catch (\Throwable $t) {
      return 'An error occurred, ' . $t->getMessage() . '; try /username/5';
    }
  }

  /**
   * Get the user ID requested, for example for /username/5 it is 5.
   *
   * @return int
   *   The requested userid.
   *
   * @throws \Exception
   */
  public function requestedUserId() : int {
    $candidate = (int) $_GET['q'];
    if (!is_int($candidate)) {
      throw new \Exception("Make sure you have a valid user id, it should be a positive int. is_int($candidate) returns FALSE.");
    }
    if ($candidate < 0) {
      throw new \Exception("Make sure you have a valid user id, it should be a positive int. is_int($candidate) is negative.");
    }
    return $candidate;
  }

  /**
   * Recursively remove a directory.
   *
   * See https://stackoverflow.com/a/3338133/1207752.
   *
   * @param string $dir
   *   A directory.
   */
  public function rrmdir(string $dir) {
    if (is_dir($dir)) {
      $objects = scandir($dir);
      foreach ($objects as $object) {
        if ($object != "." && $object != "..") {
          if (is_dir($dir."/".$object) && !is_link($dir."/".$object))
            $this->rrmdir($dir."/".$object);
          else
            unlink($dir."/".$object);
        }
      }
      rmdir($dir);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function templateName() : string {
    return 'templates/app.html';
  }

  /**
   * Given a user id, get a username from the API.
   *
   * @param int $user_id
   *   A user id such as 5.
   *
   * @return string
   *   The username from the API.
   *
   * @throws \Exception
   */
  public function usernameFromApi(int $user_id) : string {
    $result = @file_get_contents('http://varnish/username/' . $user_id);
    if ($result === FALSE) {
      throw new \Exception('Could not get information from Varnish; this might mean that user with ID ' . $user_id . ' does not exist.');
    }
    return @json_decode($result)->name;
  }

}
