<?php declare(strict_types=1);

namespace Qlimix\Tests\Http\Router;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Qlimix\Http\Router\Container;
use Qlimix\Http\Router\HttpRoute;
use Qlimix\Http\Router\Match\Builder;
use Qlimix\Http\Router\Match\MatchBuilder;
use Qlimix\Http\Router\Match\MatcherIteratorFactory;
use Qlimix\Http\Router\Method;
use Qlimix\Http\Router\Regex;
use Qlimix\Http\Router\Regex\PlaceHolder;
use Qlimix\Http\Router\Regex\Registry;
use Qlimix\Http\Router\Regex\Translator;
use Qlimix\Http\Router\RegexHttpRouter;
use Qlimix\Http\Router\Tokenize\CharTokenizer;
use Qlimix\Http\Router\Tokenize\PlaceHolderTokenizer;
use Qlimix\Http\Router\Tokenize\Tokenizer;

final class RegexHttpRoutingTest extends TestCase
{
    public function testShouldRoute(): void
    {
        $routes = $this->getRoutes();
        $routeContainer = new Container();

        foreach ($routes as $route) {
            $routeContainer->add($route);
        }

        $builder = new Builder(new MatcherIteratorFactory());

        $tokenizer = new Tokenizer([
            new PlaceHolderTokenizer(),
            new CharTokenizer(),
        ]);

        $matchBuilder = new MatchBuilder($builder, $tokenizer);

        $regexRegistry = new Registry();

        $regexRegistry->add(new PlaceHolder('id', '(\d+)'));
        $translator = new Translator([
            new Translator\PlaceHolderTranslator($routeContainer),
            new Translator\EscapeTranslator()
        ]);

        $regexBuilder = new Regex\Builder($translator);

        $regex = $regexBuilder->build($matchBuilder->build($routeContainer->getAll()));

        $matcher = new Regex\Matcher($regex);

        $router = new RegexHttpRouter($routeContainer, $matcher);

        $requests = $this->getRequests();
        foreach ($requests as $routeIndex => $requestParams) {
            $request = $this->createMock(ServerRequestInterface::class);
            $request->expects($this->once())
                ->method('getMethod')
                ->willReturn($requestParams['method']);

            $request->expects($this->once())
                ->method('getRequestTarget')
                ->willReturn($requestParams['path']);

            $route = $router->route($request);

            $this->assertSame($routes[$routeIndex]->getHandler(), $route->getHandler());
            foreach ($route->getParameters() as $parameterIndex => $parameter) {
                $this->assertSame(
                    $routes[$routeIndex]->getPlaceHolders()[$parameterIndex]->getName(), $parameter->getKey()
                );
            }
        }
    }

    /**
     * @return HttpRoute[]
     */
    private function getRoutes(): array
    {
        $route[] = new HttpRoute(Method::createGet(), '/foo', 'handler', []);
        $route[] = new HttpRoute(Method::createGet(), '/foo/{id}', 'handler', [
            new PlaceHolder('id', '([\d]+)'),
        ]);
        $route[] = new HttpRoute(Method::createGet(), '/fio', 'handler', []);
        $route[] = new HttpRoute(Method::createGet(), '/fao', 'handler', []);
        $route[] = new HttpRoute(Method::createGet(), 'foo', 'handler', []);
        $route[] = new HttpRoute(Method::createPut(), '/foo/{uuid}', 'handler', [
            new PlaceHolder('uuid', '([a-z]+)'),
        ]);
        $route[] = new HttpRoute(Method::createPost(), '/foo/{id}', 'handler', [
            new PlaceHolder('id', '([\d]+)'),
        ]);
        $route[] = new HttpRoute(Method::createGet(), '/', 'handler', []);

        return $route;
    }

    private function getRequests(): array
    {
        $routes = [];
        $routes[] = ['method' => 'get', 'path' => '/foo'];
        $routes[] = ['method' => 'get', 'path' => '/foo/123'];
        $routes[] = ['method' => 'get', 'path' => '/fio'];
        $routes[] = ['method' => 'get', 'path' => '/fao'];
        $routes[] = ['method' => 'get', 'path' => 'foo'];
        $routes[] = ['method' => 'put', 'path' => '/foo/abc'];
        $routes[] = ['method' => 'post', 'path' => '/foo/123'];
        $routes[] = ['method' => 'get', 'path' => '/'];

        return $routes;
    }
}
