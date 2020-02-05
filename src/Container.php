<?php declare(strict_types=1);

namespace Qlimix\Router;

use Qlimix\Router\Exception\ContainerException;

final class Container
{
    /** @var Route[] */
    private array $routes = [];

    public function add(Route $route): void
    {
        $this->routes[] = $route;
    }

    /**
     * @throws ContainerException
     */
    public function get(int $routeId): Route
    {
        if (isset($this->routes[$routeId])) {
            return $this->routes[$routeId];
        }

        throw new ContainerException('route not found');
    }
}
