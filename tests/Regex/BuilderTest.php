<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Regex;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\Container;
use Qlimix\Router\Match\Match;
use Qlimix\Router\Match\Tokens;
use Qlimix\Router\Method;
use Qlimix\Router\Regex\Builder;
use Qlimix\Router\Regex\PlaceHolder;
use Qlimix\Router\Regex\Translator;
use Qlimix\Router\Regex\Translator\EscapeTranslator;
use Qlimix\Router\Regex\Translator\PlaceHolderTranslator;
use Qlimix\Router\Route;
use Qlimix\Router\Tokenize\Token;

final class BuilderTest extends TestCase
{
    public function testShouldBuild(): void
    {
        $routeContainer = new Container();

        $routeContainer->add(
            new Route(Method::createGet(), '/foo', 'foo', [])
        );

        $routeContainer->add(
            new Route(Method::createGet(), '/foo/{id}', 'foo.id', [
                new PlaceHolder('id', '([\d]+)')
            ])
        );

        $routeContainer->add(
            new Route(Method::createGet(), '/foo/bar', 'foo.bar', [])
        );

        $match = Match::createRoot();
        $next = $match->append(new Tokens([
                Token::createChar('/'),
            ],
            null
        ))->append(new Tokens([
                Token::createChar('f'),
                Token::createChar('o'),
                Token::createChar('o'),
            ],
            0
        ))->append(new Tokens([
            Token::createChar('/'),
            ],
            null
        ));

        $next->append(new Tokens([
                Token::createPlaceholder('{id}'),
            ],
            1
        ));

        $next->append(new Tokens([
            Token::createChar('b'),
            Token::createChar('a'),
            Token::createChar('r'),
            ],
            2
        ));

        $builder = new Builder(new Translator([
            new EscapeTranslator(),
            new PlaceHolderTranslator($routeContainer),
        ]));

        $match = $builder->build($match);

        $this->assertSame('^(?|\/(?|foo(?|(*MARK:0)|\/(?|([\d]+)(*MARK:1)|bar(*MARK:2)))))$', $match);
    }

    public function testShouldBuildWithSeparatePaths(): void
    {
        $routeContainer = new Container();

        $routeContainer->add(
            new Route(Method::createGet(), '/foo', 'foo', [])
        );

        $routeContainer->add(
            new Route(Method::createGet(), '/foo/bar', 'foo.bar', [])
        );

        $routeContainer->add(
            new Route(Method::createGet(), '/foo/{id}', 'foo.id', [
                new PlaceHolder('id', '([\d]+)')
            ])
        );

        $match = Match::createRoot();
        $match->append(new Tokens([
                Token::createChar('a'),
            ],
            0
        ))->append(new Tokens([
            Token::createChar('b'),
        ],
            0
        ));

        $match->append(new Tokens([
                Token::createChar('b'),
            ],
            1
        ));

        $builder = new Builder(new Translator([
            new EscapeTranslator(),
            new PlaceHolderTranslator($routeContainer),
        ]));

        $match = $builder->build($match);

        $this->assertSame('^(?|a(?|(*MARK:0)|b(*MARK:0)))$|^(?|b(*MARK:1))$', $match);
    }
}
