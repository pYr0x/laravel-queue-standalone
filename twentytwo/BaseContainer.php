<?php

namespace twentytwo;

use Illuminate\Container\Container;

class BaseContainer extends Container {

  /**
   * Determine if the application is in maintenance mode.
   * @return bool
   */
  public function isDownForMaintenance() {
    return false;
  }
}
