<?php

namespace myproject\Tests;

/**
 * Represents a database test.
 */
class Database extends Test {

  public function cached() : bool {
    return TRUE;
  }

  public function cachedHuman() : string {
    return 'n/a';
  }

  /**
   * {@inheritdoc}
   */
  public function name() : string {
    return 'Database';
  }

  /**
   * {@inheritdoc}
   */
  public function results() : array {
    $servername = "mysql";
    $username = "root";
    $password = $_ENV['MYSQL_ROOT_PASSWORD'];
    $dbname = "drupal";

    // Create connection
    $conn = new \mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
      throw new \Exception("Connection failed: " . $conn->connect_error);
    }

    $sql = "select name from users_field_data where uid = 5;";
    $result = $conn->query($sql);
    $conn->close();

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            return [
              'real_username' => $row['name'],
            ];
        }
    }
    throw new \Exception('Could not find username');
  }

}
