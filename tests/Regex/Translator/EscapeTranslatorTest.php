<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Regex\Translator;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\Regex\Translator\EscapeTranslator;
use Qlimix\Router\Tokenize\Token;

final class EscapeTranslatorTest extends TestCase
{
    public function testShouldEscape(): void
    {
        $escape = new EscapeTranslator();

        $token = Token::createChar('*');

        $this->assertTrue($escape->can($token, null));
        $this->assertSame('\*', $escape->translate($token, null));
    }

    public function testShouldNotEscape(): void
    {
        $escape = new EscapeTranslator();

        $token = Token::createChar('a');

        $this->assertFalse($escape->can($token, null));
    }
}
