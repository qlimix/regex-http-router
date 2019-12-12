<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Parser;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\Match\Exception\LastMatchException;
use Qlimix\Router\Match\Matcher;
use Qlimix\Router\Tokenize\Token;
use Qlimix\Router\Tokenize\Tokenized;

final class MatcherWithPlaceholdersTest extends TestCase
{
    public function testShouldMatch(): void
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
