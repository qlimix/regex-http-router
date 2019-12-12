<?php declare(strict_types=1);

namespace Qlimix\Tests\Router;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Qlimix\Router\Container;
use Qlimix\Router\Exception\RouterException;
use Qlimix\Router\Locator\LocatorInterface;
use Qlimix\Router\QlimixRouter;
use Qlimix\Router\Regex\Matcher;

final class QlimixRouterTest extends TestCase
{
    public function testShouldRoute(): void
    {
        $locator = $this->createMock(LocatorInterface::class);
        $router = new QlimixRouter(
            new Container(),
            new Matcher(
                '^(?|\/f(?|oo(?|(*MARK:0)|\/(\d+)(*MARK:4))|io(*MARK:1)|ao(*MARK:2)))$|^(?|foo(*MARK:3))$'
            ),
            $locator
        );

        $serverRequest = $this->createMock(ServerRequestInterface::class);
        $serverRequest->expects($this->once())
            ->method('getRequestTarget')
            ->willReturn('/foo/123');

        $this->expectException(RouterException::class);
        $router->route($serverRequest);
    }
}
