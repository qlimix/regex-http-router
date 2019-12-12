<?php declare(strict_types=1);

namespace Qlimix\Router;

use Psr\Http\Message\ServerRequestInterface;
use Qlimix\Router\Exception\RouteNotFoundException;
use Qlimix\Router\Exception\RouterException;
use Qlimix\Router\Locator\LocatorInterface;
use Qlimix\Router\Regex\Exception\NoMatchFoundException;
use Qlimix\Router\Regex\Matcher;
use Throwable;

final class QlimixRouter implements RouterInterface
{
    private Container $container;

    private Matcher $matcher;

    private LocatorInterface $locator;

    public function __construct(Container $container, Matcher $matcher, LocatorInterface $locator)
    {
        $this->container = $container;
        $this->matcher = $matcher;
        $this->locator = $locator;
    }

    /**
     * @inheritDoc
     */
    public function route(ServerRequestInterface $request): RouteRequestHandler
    {
        try {
            $result = $this->matcher->match($request->getRequestTarget());

            $route = $this->container->get($result->getId());

            $parameters = $result->getParameters();
            $i = 0;
            foreach ($route->getPlaceHolders() as $placeHolder) {
                $request = $request->withAttribute($placeHolder->getPlaceHolder(), $parameters[$i]);
                $i++;
            }

            $handler = $this->locator->locate($route->getHandler());
        } catch (NoMatchFoundException $exception) {
            throw new RouteNotFoundException('Route not found', 0, $exception);
        } catch (Throwable $exception) {
            throw new RouterException('Router failed', 0, $exception);
        }

        return new RouteRequestHandler($handler, $request);
    }
}
