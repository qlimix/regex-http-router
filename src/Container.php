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

    public function has(int $routeId): bool
    {
        return isset($this->routes[$routeId]);
    }

    /**
     * @throws ContainerException
     */
    public function get(int $routeId): Route
    {
        if ($this->has($routeId)) {
            return $this->routes[$routeId];
        }

        throw new ContainerException('route not found');
    }
}
