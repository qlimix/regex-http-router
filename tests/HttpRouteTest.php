<?php declare(strict_types=1);

namespace Qlimix\Tests\Router;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\HttpRoute;
use Qlimix\Router\Method;
use Qlimix\Router\Regex\PlaceHolder;

final class HttpRouteTest extends TestCase
{
    public function testShouldHttpRoute(): void
    {
        $method = Method::createGet();

        $path = '/foo/{id}';
        $handler = 'foo.item.handler';
        $placeHolders = [new PlaceHolder('id', '([\d]+)')];

        $httpRoute = new HttpRoute($method, $path, $handler, $placeHolders);

        $this->assertTrue($method->equals(Method::createGet()));
        $this->assertSame($method, $httpRoute->getMethod());
        $this->assertSame($path, $httpRoute->getPath());
        $this->assertSame($handler, $httpRoute->getHandler());
        $this->assertSame($placeHolders, $httpRoute->getPlaceHolders());
        $this->assertSame($method->toString().$path, $httpRoute->toString());
    }
}
