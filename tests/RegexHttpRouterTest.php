<?php declare(strict_types=1);

namespace Qlimix\Tests\Router;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Qlimix\Router\Container;
use Qlimix\Router\Exception\RouteNotFoundException;
use Qlimix\Router\Exception\RouterException;
use Qlimix\Router\HttpRoute;
use Qlimix\Router\Method;
use Qlimix\Router\RegexHttpRouter;
use Qlimix\Router\Regex\Matcher;
use Qlimix\Router\Regex\PlaceHolder;

final class RegexHttpRouterTest extends TestCase
{
    private const ROUTES_REGEX = '^(?|get\/foo(?|(*MARK:0)|\/([\d]+)(*MARK:1)))$|^(?|post\/foo\/([\d]+)(*MARK:2))$';

    public function testShouldRoute(): void
    {
        $container = new Container();
        $router = new RegexHttpRouter($container, new Matcher(self::ROUTES_REGEX));

        $getFooListHandler = 'get.foo.handler';
        $getFooItemHandler = 'get.foo.item.handler';
        $postFooItemHandler = 'post.foo.item.handler';

        $container->add(new HttpRoute(Method::createGet(), '/foo', $getFooListHandler, []));

        $container->add(new HttpRoute(Method::createGet(), '/foo/{id}', $getFooItemHandler, [
            new PlaceHolder('id', '([\d]+)')
        ]));

        $container->add(new HttpRoute(Method::createPost(), '/foo/{id}', $postFooItemHandler, [
            new PlaceHolder('id', '([\d]+)')
        ]));

        $request = $this->createMock(ServerRequestInterface::class);

        $request->expects($this->once())
            ->method('getMethod')
            ->willReturn('get');
        $this->expectException(RouterException::class);

        $request->expects($this->once())
            ->method('getRequestTarget')
            ->willReturn('/foo');

        $route = $router->route($request);

        $this->assertSame($getFooListHandler, $route->getHandler());
        $this->assertCount(0, $route->getParameters());

        $request = $this->createMock(ServerRequestInterface::class);

        $request->expects($this->once())
            ->method('getMethod')
            ->willReturn('get');
        $this->expectException(RouterException::class);

        $request->expects($this->once())
            ->method('getRequestTarget')
            ->willReturn('/foo/123');

        $route = $router->route($request);

        $this->assertSame($getFooItemHandler, $route->getHandler());
        $this->assertSame('id', $route->getParameters()[0]->getKey());
        $this->assertSame('123', $route->getParameters()[0]->getValue());

        $request = $this->createMock(ServerRequestInterface::class);

        $request->expects($this->once())
            ->method('getMethod')
            ->willReturn('post');
        $this->expectException(RouterException::class);

        $request->expects($this->once())
            ->method('getRequestTarget')
            ->willReturn('/foo/123');

        $route = $router->route($request);

        $this->assertSame($postFooItemHandler, $route->getHandler());
        $this->assertSame('id', $route->getParameters()[0]->getKey());
        $this->assertSame('123', $route->getParameters()[0]->getValue());
    }

    public function testShouldThrowOnContainerException(): void
    {
        $container = new Container();
        $router = new RegexHttpRouter($container, new Matcher(self::ROUTES_REGEX));

        $request = $this->createMock(ServerRequestInterface::class);

        $request->expects($this->once())
            ->method('getMethod')
            ->willReturn('get');
        $this->expectException(RouterException::class);

        $request->expects($this->once())
            ->method('getRequestTarget')
            ->willReturn('/foo');

        $this->expectException(RouterException::class);
        $router->route($request);
    }

    public function testShouldThrowOnRouteNotFound(): void
    {
        $container = new Container();
        $router = new RegexHttpRouter($container, new Matcher(self::ROUTES_REGEX));

        $request = $this->createMock(ServerRequestInterface::class);

        $request->expects($this->once())
            ->method('getMethod')
            ->willReturn('get');
        $this->expectException(RouterException::class);

        $request->expects($this->once())
            ->method('getRequestTarget')
            ->willReturn('/bar');

        $this->expectException(RouteNotFoundException::class);
        $router->route($request);
    }
}
