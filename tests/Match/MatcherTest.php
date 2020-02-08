<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Match;

use PHPUnit\Framework\TestCase;
use Qlimix\Http\Router\Match\Exception\LastMatchException;
use Qlimix\Http\Router\Match\Matcher;
use Qlimix\Http\Router\Tokenize\Token;
use Qlimix\Http\Router\Tokenize\Tokenized;

final class MatcherTest extends TestCase
{
    public function testShouldMatch(): void
    {
        $matcher = new Matcher(
            new Tokenized(
                1,
                [
                    Token::createChar('/'),
                    Token::createChar('f'),
                    Token::createChar('o'),
                    Token::createChar('o'),
                ]
            ),
            [
                new Tokenized(
                    2,
                    [
                        Token::createChar('/'),
                        Token::createChar('f'),
                    ]
                ),
                new Tokenized(
                    3,
                    [
                        Token::createChar('/'),
                        Token::createChar('f'),
                        Token::createChar('o'),
                    ]
                ),
                new Tokenized(
                    4,
                    [
                        Token::createChar('/'),
                        Token::createChar('f'),
                        Token::createChar('a'),
                    ]
                ),
            ]
        );

        $match = $matcher->match();

        $this->assertSame('/', $match->getTokens()[0]->getToken());
        $this->assertSame('f', $match->getTokens()[1]->getToken());
        $this->assertNull($match->getId());

        $match = $matcher->match();

        $this->assertSame('o', $match->getTokens()[0]->getToken());
        $this->assertNull($match->getId());

        $match = $matcher->match();

        $this->assertSame('o', $match->getTokens()[0]->getToken());
        $this->assertSame(1, $match->getId());
    }

    public function testShouldThrowLastMatchException(): void
    {
        $matcher = new Matcher(
            new Tokenized(
                1,
                [
                    Token::createChar('/'),
                    Token::createChar('f'),
                ]
            ),
            [
                new Tokenized(
                    2,
                    [
                        Token::createChar('/'),
                    ]
                )
            ]
        );

        $match = $matcher->match();

        $this->assertSame('/', $match->getTokens()[0]->getToken());
        $this->assertNull($match->getId());


        $match = $matcher->match();

        $this->assertSame('f', $match->getTokens()[0]->getToken());
        $this->assertSame(1, $match->getId());

        $this->expectException(LastMatchException::class);
        $matcher->match();
    }

    public function testShouldMatchWithSmallerTokenSet(): void
    {
        $matcher = new Matcher(
            new Tokenized(
                1,
                [
                    Token::createChar('/'),
                    Token::createChar('f'),
                ]
            ),
            [
                new Tokenized(
                    2,
                    [
                        Token::createChar('/'),
                        Token::createChar('f'),
                        Token::createChar('a'),
                    ]
                ),
                new Tokenized(
                    3,
                    [
                        Token::createChar('/'),
                        Token::createChar('f'),
                        Token::createChar('o'),
                        Token::createChar('o'),
                    ]
                ),
                new Tokenized(
                    4,
                    [
                        Token::createChar('/'),
                        Token::createChar('f'),
                        Token::createChar('a'),
                        Token::createChar('a'),
                    ]
                ),
            ]
        );

        $match = $matcher->match();

        $this->assertSame('/', $match->getTokens()[0]->getToken());
        $this->assertSame('f', $match->getTokens()[1]->getToken());
        $this->assertSame(1, $match->getId());
    }

    public function testShouldMatchWithWhileRunningOutOfTokenizeds(): void
    {
        $matcher = new Matcher(
            new Tokenized(
                1,
                [
                    Token::createChar('/'),
                    Token::createChar('f'),
                    Token::createChar('o'),
                    Token::createChar('o'),
                    Token::createChar('b'),
                    Token::createChar('a'),
                    Token::createChar('r'),
                ]
            ),
            [
                new Tokenized(
                    3,
                    [
                        Token::createChar('f'),
                    ]
                ),
            ]
        );

        $match = $matcher->match();

        $this->assertSame('/', $match->getTokens()[0]->getToken());
        $this->assertSame('f', $match->getTokens()[1]->getToken());
        $this->assertSame('o', $match->getTokens()[2]->getToken());
        $this->assertSame('o', $match->getTokens()[3]->getToken());
        $this->assertSame(1, $match->getId());
    }


    public function testShouldMatch2(): void
    {
        $tokens = [
            Token::createChar('/'),
            Token::createChar('f'),
            Token::createChar('o'),
            Token::createChar('o'),
            Token::createChar('/'),
            Token::createChar('{id}'),
            Token::createChar('/'),
            Token::createChar('b'),
            Token::createChar('a'),
            Token::createChar('r'),
        ];
        $parsedRoute = new Tokenized(1, $tokens);

        $tokens2 = [
            Token::createChar('/'),
            Token::createChar('f'),
            Token::createChar('o'),
            Token::createChar('o'),
        ];

        $parsedRoute2 = new Tokenized(2, $tokens2);

        $tokens3 = [
            Token::createChar('/'),
            Token::createChar('f'),
            Token::createChar('o'),
            Token::createChar('i'),
        ];

        $parsedRoute3 = new Tokenized(3, $tokens3);

        $tokens4 = [
            Token::createChar('/'),
            Token::createChar('f'),
            Token::createChar('o'),
            Token::createChar('o'),
            Token::createChar('/'),
            Token::createChar('{id}'),
            Token::createChar('/'),
            Token::createChar('f'),
            Token::createChar('o'),
            Token::createChar('o'),
        ];

        $parsedRoute4 = new Tokenized(4, $tokens4);

        $parsedRoutes = [
            $parsedRoute2,
            $parsedRoute3,
            $parsedRoute4,
        ];

        $matcher = new Matcher($parsedRoute, $parsedRoutes);

        $matches = $matcher->match();
        $this->assertCount(3, $matches->getTokens());

        $matches = $matcher->match();
        $this->assertCount(1, $matches->getTokens());

        $matches = $matcher->match();
        $this->assertCount(3, $matches->getTokens());

        $matches = $matcher->match();
        $this->assertCount(3, $matches->getTokens());
    }

    public function testShouldMatchShortest(): void
    {
        $tokens = [
            Token::createChar('/'),
            Token::createChar('f'),
            Token::createChar('o'),
        ];
        $parsedRoute = new Tokenized(1, $tokens);

        $tokens2 = [
            Token::createChar('/'),
            Token::createChar('f'),
            Token::createChar('o'),
            Token::createChar('o'),
        ];

        $parsedRoute2 = new Tokenized(2, $tokens2);

        $tokens3 = [
            Token::createChar('/'),
            Token::createChar('f'),
            Token::createChar('o'),
            Token::createChar('i'),
        ];

        $parsedRoute3 = new Tokenized(3, $tokens3);

        $tokens4 = [
            Token::createChar('/'),
            Token::createChar('f'),
            Token::createChar('o'),
            Token::createChar('o'),
            Token::createChar('/'),
        ];

        $parsedRoute4 = new Tokenized(4, $tokens4);

        $parsedRoutes = [
            $parsedRoute2,
            $parsedRoute3,
            $parsedRoute4,
        ];

        $matcher = new Matcher($parsedRoute, $parsedRoutes);

        $matches = $matcher->match();
        $this->assertCount(3, $matches->getTokens());

        $this->expectException(LastMatchException::class);

        $matcher->match();
    }

    public function testShouldMatchLongest(): void
    {
        $tokens = [
            Token::createChar('/'),
            Token::createChar('f'),
            Token::createChar('o'),
            Token::createChar('o'),
            Token::createChar('/'),
            Token::createChar('{id}'),
            Token::createChar('/'),
            Token::createChar('b'),
            Token::createChar('a'),
            Token::createChar('r'),
        ];
        $parsedRoute = new Tokenized(1, $tokens);

        $tokens2 = [
            Token::createChar('/'),
            Token::createChar('f'),
            Token::createChar('o'),
            Token::createChar('o'),
        ];

        $parsedRoute2 = new Tokenized(2, $tokens2);

        $tokens3 = [
            Token::createChar('/'),
            Token::createChar('f'),
            Token::createChar('o'),
            Token::createChar('i'),
        ];

        $parsedRoute3 = new Tokenized(3, $tokens3);

        $tokens4 = [
            Token::createChar('/'),
            Token::createChar('f'),
            Token::createChar('o'),
            Token::createChar('o'),
            Token::createChar('/'),
            Token::createChar('{id}'),
        ];

        $parsedRoute4 = new Tokenized(4, $tokens4);

        $parsedRoutes = [
            $parsedRoute2,
            $parsedRoute3,
            $parsedRoute4,
        ];

        $matcher = new Matcher($parsedRoute, $parsedRoutes);

        $matches = $matcher->match();
        $this->assertCount(3, $matches->getTokens());

        $matches = $matcher->match();
        $this->assertCount(1, $matches->getTokens());

        $matches = $matcher->match();
        $this->assertCount(2, $matches->getTokens());

        $matches = $matcher->match();
        $this->assertCount(4, $matches->getTokens());
    }

    public function testShouldDifferentBranches(): void
    {
        $tokens = [
            Token::createChar('/'),
            Token::createChar('f'),
            Token::createChar('o'),
            Token::createChar('o'),
        ];
        $parsedRoute = new Tokenized(1, $tokens);

        $tokens2 = [
            Token::createChar('f'),
            Token::createChar('o'),
            Token::createChar('o'),
        ];

        $parsedRoute2 = new Tokenized(2, $tokens2);

        $matcher = new Matcher($parsedRoute, [$parsedRoute2]);

        $matches = $matcher->match();
        $this->assertCount(4, $matches->getTokens());

        $matcher = new Matcher($parsedRoute2, [$parsedRoute]);

        $matches = $matcher->match();
        $this->assertCount(3, $matches->getTokens());
    }
}
