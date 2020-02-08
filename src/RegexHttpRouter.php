<?php declare(strict_types=1);

namespace Qlimix\Http\Router;

use Psr\Http\Message\ServerRequestInterface;
use Qlimix\Http\Router\Exception\RouteNotFoundException;
use Qlimix\Http\Router\Exception\RouterException;
use Qlimix\Http\Router\Regex\Exception\NoMatchFoundException;
use Qlimix\Http\Router\Regex\Matcher;
use Throwable;

final class RegexHttpRouter implements HttpRouterInterface
{
    private Container $container;

    private Matcher $matcher;

    public function __construct(Container $container, Matcher $matcher)
    {
        $this->container = $container;
        $this->matcher = $matcher;
    }

    /**
     * @inheritDoc
     */
    public function route(ServerRequestInterface $request): Route
    {
        try {
            $result = $this->matcher->match($request->getMethod().$request->getRequestTarget());

            $route = $this->container->get($result->getId());

            $placeHolders = $route->getPlaceHolders();
            $parameters = [];
            foreach ($result->getParameters() as $index => $parameter) {
                $parameters[] = new Parameter($placeHolders[$index]->getName(), $parameter);
            }

            return new Route($route->getHandler(), $parameters);
        } catch (NoMatchFoundException $exception) {
            throw new RouteNotFoundException('Route not found', 0, $exception);
        } catch (Throwable $exception) {
            throw new RouterException('Router failed', 0, $exception);
        }
    }
}
