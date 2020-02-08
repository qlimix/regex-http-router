<?php declare(strict_types=1);

namespace Qlimix\Tests\Router;

use PHPUnit\Framework\TestCase;
use Qlimix\Http\Router\Container;
use Qlimix\Http\Router\Exception\ContainerException;
use Qlimix\Http\Router\Method;
use Qlimix\Http\Router\HttpRoute;

final class ContainerTest extends TestCase
{
    public function testShouldContainer(): void
    {
        $foo = 'foo';
        $bar = 'bar';
        $fooHandler = 'fooHandler';
        $barHandler = 'barHandler';

        $container = new Container();
        $container->add(new HttpRoute(Method::createGet(), $foo, $fooHandler, []));
        $container->add(new HttpRoute(Method::createPost(), $bar, $barHandler, []));

        $route =  $container->get(0);
        $this->assertTrue($route->getMethod()->equals(Method::createGet()));
        $this->assertSame($foo, $route->getPath());
        $this->assertSame($fooHandler, $route->getHandler());

        $route =  $container->get(1);
        $this->assertTrue($route->getMethod()->equals(Method::createPost()));
        $this->assertSame($bar, $route->getPath());
        $this->assertSame($barHandler, $route->getHandler());

        $this->assertCount(2, $container->getAll());
    }

    public function testShouldThrowOnRouteNotFound(): void
    {
        $container = new Container();

        $this->expectException(ContainerException::class);
        $container->get(0);
    }
}
