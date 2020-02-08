<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Tokenize;

use PHPUnit\Framework\TestCase;
use Qlimix\Http\Router\Tokenize\Token;
use Qlimix\Http\Router\Tokenize\Tokenized;

final class TokenizedTest extends TestCase
{
    public function testShouldTokenize(): void
    {
        $id = 1;
        $tokens = [
            Token::createChar('a'),
            Token::createPlaceholder('{id}'),
        ];

        $tokenized = new Tokenized($id, $tokens);

        $this->assertSame($id, $tokenized->getId());
        $this->assertSame($tokens[0]->getToken(), $tokenized->getTokens()[0]->getToken());
        $this->assertSame($tokens[1]->getToken(), $tokenized->getTokens()[1]->getToken());
        $this->assertSame(2, $tokenized->getTokenCount());
    }
}
