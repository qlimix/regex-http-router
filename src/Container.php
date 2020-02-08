<?php declare(strict_types=1);

namespace Qlimix\Http\Router;

use Qlimix\Http\Router\Exception\ContainerException;

final class Container
{
    /** @var HttpRoute[] */
    private array $routes = [];

    public function add(HttpRoute $route): void
    {
        $this->routes[] = $route;
    }

    /**
     * @throws ContainerException
     */
    public function get(int $routeId): HttpRoute
    {
        if (isset($this->routes[$routeId])) {
            return $this->routes[$routeId];
        }

        throw new ContainerException('route not found');
    }

    /**
     * @return HttpRoute[]
     */
    public function getAll(): array
    {
        return $this->routes;
    }
}
