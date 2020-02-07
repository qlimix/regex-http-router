<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Match;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\Match\Exception\TokensException;
use Qlimix\Router\Match\Tokens;
use Qlimix\Router\Tokenize\Token;

final class TokensTest extends TestCase
{
    public function testShouldHoldTokens(): void
    {
        $tokens = new Tokens(
            [
                Token::createChar('/'),
                Token::createChar('f'),
            ],
            null
        );

        $this->assertNull($tokens->getId());
        $this->assertCount(2, $tokens->getTokens());
        $this->assertSame('/f', $tokens->toString());
    }

    public function testShouldBeEqual(): void
    {
        $tokens = new Tokens(
            [
                Token::createChar('/'),
                Token::createChar('f'),
            ],
            null
        );

        $this->assertTrue($tokens->equals(new Tokens(
            [
                Token::createChar('/'),
                Token::createChar('f'),
            ],
            null
        )));
    }

    public function testShouldCanPromote(): void
    {
        $tokens = new Tokens(
            [
                Token::createChar('/'),
                Token::createChar('f'),
            ],
            null
        );

        $tokens->promote(1);
        $this->assertSame(1, $tokens->getId());
    }

    public function testShouldThrowOnAlreadyPromoted(): void
    {
        $tokens = new Tokens(
            [
                Token::createChar('/'),
                Token::createChar('f'),
            ],
            null
        );

        $tokens->promote(1);
        $this->expectException(TokensException::class);
        $tokens->promote(2);
    }
}
