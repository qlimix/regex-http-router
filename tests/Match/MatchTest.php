<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Match;

use PHPUnit\Framework\TestCase;
use Qlimix\Http\Router\Match\Exception\MatchException;
use Qlimix\Http\Router\Match\Match;
use Qlimix\Http\Router\Match\Tokens;
use Qlimix\Http\Router\Tokenize\Token;
use Qlimix\Http\Router\Tokenize\TokenType;

final class MatchTest extends TestCase
{
    public function testShouldCreateRoot(): void
    {
        $match = Match::createRoot();

        $this->assertSame($match->getChildren(), []);
        $this->assertSame($match->getTokens()->getTokens(), []);
        $this->assertNull($match->getTokens()->getId());
    }

    public function testShouldMatchAppend(): void
    {
        $match = Match::createRoot();

        $match->append(new Tokens([Token::createChar('f')], 1));

        $this->assertSame($match->getChildren()[0]->getTokens()->getTokens()[0]->getToken(), 'f');
        $this->assertTrue(
            $match->getChildren()[0]->getTokens()->getTokens()[0]->getType()->equals(TokenType::createChar())
        );
    }

    public function testShouldMatchAppendWithoutCreatingNewMatch(): void
    {
        $match = Match::createRoot();

        $match->append(new Tokens([Token::createChar('f')], 1));
        $match->append(new Tokens([Token::createChar('f')], null));
        $match->append(new Tokens([Token::createChar('f'), Token::createChar('e')], null));

        $this->assertSame($match->getChildren()[0]->getTokens()->getTokens()[0]->getToken(), 'f');
        $this->assertTrue(
            $match->getChildren()[0]->getTokens()->getTokens()[0]->getType()->equals(TokenType::createChar())
        );

        $this->assertCount(2, $match->getChildren());
    }

    public function testShouldThrowOnAlreadyPromotedTokens(): void
    {
        $match = Match::createRoot();

        $match->append(new Tokens([Token::createChar('f')], 1));
        $this->expectException(MatchException::class);
        $match->append(new Tokens([Token::createChar('f')], 1));
    }
}
