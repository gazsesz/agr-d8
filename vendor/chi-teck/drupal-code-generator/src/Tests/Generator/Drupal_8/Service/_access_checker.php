<?php

namespace Drupal\example\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use Symfony\Component\Routing\Route;

/**
 * Checks if passed parameter matches the route configuration.
 */
class FooAccessChecker implements AccessInterface {

  /**
   * Access callback.
   *
   * @param \Symfony\Component\Routing\Route $route
   *   The route to check against.
   * @param \ExampleInterface $parameter
   *   The parameter to test.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(Route $route, ExampleInterface $param) {
    return AccessResult::allowedIf($param->getSomeValue() == $route->getRequirement('_foo'));
  }

}
