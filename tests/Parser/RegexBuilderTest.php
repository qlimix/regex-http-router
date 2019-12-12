<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Parser;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\Container;
use Qlimix\Router\Match\Builder;
use Qlimix\Router\Match\MatchBuilder;
use Qlimix\Router\Method;
use Qlimix\Router\Regex;
use Qlimix\Router\Regex\Translator;
use Qlimix\Router\Regex\PlaceHolder;
use Qlimix\Router\Regex\Registry;
use Qlimix\Router\Route;
use Qlimix\Router\Tokenize\CharTokenizer;
use Qlimix\Router\Tokenize\PlaceHolderTokenizer;
use Qlimix\Router\Tokenize\Tokenizer;

final class RegexBuilderTest extends TestCase
{
    protected function setUp(): void
    {
        ini_set('xdebug.var_display_max_depth', '15');
        error_reporting(E_ALL);
    }

    public function testShouldBuild(): void
    {
        $route = new Route(Method::createGet(), '/foo', 'handler', []);
        $route2 = new Route(Method::createGet(), '/foo/{id}', 'handler', []);
        $route3 = new Route(Method::createGet(), '/fio', 'handler', []);
        $route4 = new Route(Method::createGet(), '/fao', 'handler', []);
        $route5 = new Route(Method::createGet(), 'foo', 'handler', []);
//        $route6 = new Route('method', '/foo/{uuid}', 'handler', []);

        $routes = [
            $route,
            $route2,
            $route3,
            $route4,
            $route5,
//            $route6,
        ];

        $builder = new Builder();

        $tokenizer = new Tokenizer([
            new PlaceHolderTokenizer(),
            new CharTokenizer(),
        ]);

        $matchBuilder = new MatchBuilder($builder, $tokenizer);

        $regexRegistry = new Registry();
        $routeContainer = new Container();

        foreach ($routes as $route) {
            $routeContainer->add($route);
        }

        $regexRegistry->add(new PlaceHolder('id', '(\d+)'));
        $translator = new Translator([
            new Translator\PlaceHolderTranslator($routeContainer),
            new Translator\EscapeTranslator()
        ]);
        $regexBuilder = new Regex\Builder($translator);

        var_dump($regexBuilder->build($matchBuilder->build($routes)));
    }
}
