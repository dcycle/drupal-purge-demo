<?php

/**
 * @file
 * Class autoloader.
 */

spl_autoload_register(function ($class) {
  if (substr($class, 0, strlen('myproject\\')) == 'myproject\\') {
    $class = preg_replace('/^myproject\\\\/', '', $class);
    $path = 'src/' . str_replace('\\', '/', $class) . '.php';
    require_once $path;
  }
});
