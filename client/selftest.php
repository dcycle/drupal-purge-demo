<?php
/**
 * @file
 * Entrypoint.
 */

require_once 'autoload.php';

use myproject\Selftest;

print(Selftest::instance()->getPage());
