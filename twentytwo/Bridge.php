<?php
/**
 * Copyright (c) 2007-2019 Julian Kern, twentytwo Solutions (http://www.22-solutions.de)
 * All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited Proprietary and confidential.
 */

namespace twentytwo;


use Interop\Container\ContainerInterface;

class Bridge implements \ArrayAccess {
  private $container = array();

  public function __construct(ContainerInterface $container) {
    $this->container = $container;
  }

  public function offsetSet($offset, $value) {
    return;
  }

  public function offsetExists($offset) {
    return $this->container->has($offset);
  }

  public function offsetUnset($offset) {
    return;
  }

  public function offsetGet($offset) {
    return $this->container->has($offset) ? $this->container->get($offset) : null;
  }
}
