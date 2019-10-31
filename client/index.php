<?php
/**
 * @file
 * Entrypoint.
 */

require_once 'autoload.php';

use myproject\App;

print(App::instance()->getPage());
