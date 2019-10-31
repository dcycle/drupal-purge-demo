<?php

namespace myproject\Templatable;

/**
 * Class which can be displayed using a template.
 */
abstract class Templatable {

  /**
   * Return a display for this template.
   *
   * @param array $variables
   *   Variables to use in the template.
   *
   * @return string
   *   The contents to display.
   */
  public function display(array $variables = []) : string {
    $contents = file_get_contents($this->templateName());

    foreach ($variables as $key => $value) {
      $contents = str_replace($key, $value, $contents);
    }

    return $contents;
  }

  /**
   * Get the template name to display this template.
   */
  abstract public function templateName() : string;

}
