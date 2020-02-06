<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Tokenize;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\Tokenize\TokenType;

final class TokenTypeTest extends TestCase
{
    public function testShouldTokenType(): void
    {
        $char = TokenType::createChar();
        $placeHolder = TokenType::createPlaceHolder();

        $this->assertTrue($char->equals(TokenType::createChar()));
        $this->assertSame('char', $char->getType());
        $this->assertTrue($char->isChar());
        $this->assertFalse($char->isPlaceHolder());

        $this->assertTrue($placeHolder->equals(TokenType::createPlaceHolder()));
        $this->assertSame('placeholder', $placeHolder->getType());
        $this->assertTrue($placeHolder->isPlaceHolder());
        $this->assertFalse($placeHolder->isChar());
    }
}
