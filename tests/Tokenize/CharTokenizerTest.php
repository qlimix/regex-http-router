<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Tokenize;

use PHPUnit\Framework\TestCase;
use Qlimix\Http\Router\Tokenize\CharTokenizer;
use Qlimix\Http\Router\Tokenize\Token;

final class CharTokenizerTest extends TestCase
{
    public function testShouldTokenize(): void
    {
        $charTokenizer = new CharTokenizer();

        $this->assertTrue($charTokenizer->canTokenize('a'));
        $this->assertTrue(Token::createChar('a')->equals($charTokenizer->tokenize('a', 0)));
    }
}
