<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Tokenize;

use PHPUnit\Framework\TestCase;
use Qlimix\Http\Router\Tokenize\Token;
use Qlimix\Http\Router\Tokenize\TokenType;

final class TokenTest extends TestCase
{
    public function testShouldToken(): void
    {
        $charString = 'a';
        $char = Token::createChar($charString);

        $placeHolderString = '{id}';
        $placeHolder = Token::createPlaceholder($placeHolderString);

        $this->assertSame($charString, $char->getToken());
        $this->assertTrue($char->getType()->equals(TokenType::createChar()));
        $this->assertSame(1, $char->getLength());
        $this->assertTrue(Token::createChar($charString)->equals($char));

        $this->assertSame($placeHolderString, $placeHolder->getToken());
        $this->assertTrue($placeHolder->getType()->equals(TokenType::createPlaceHolder()));
        $this->assertSame(4, $placeHolder->getLength());
        $this->assertTrue(Token::createPlaceholder($placeHolderString)->equals($placeHolder));
    }
}
