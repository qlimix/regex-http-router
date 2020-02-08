<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Tokenize;

use PHPUnit\Framework\TestCase;
use Qlimix\Http\Router\Tokenize\CharTokenizer;
use Qlimix\Http\Router\Tokenize\Exception\TokenizerNotFoundException;
use Qlimix\Http\Router\Tokenize\PlaceHolderTokenizer;
use Qlimix\Http\Router\Tokenize\Token;
use Qlimix\Http\Router\Tokenize\Tokenizer;

final class TokenizerTest extends TestCase
{
    public function testShouldTokenize(): void
    {
        $tokenizer = new Tokenizer([
            new PlaceHolderTokenizer(),
            new CharTokenizer(),
        ]);

        $tokenized = $tokenizer->tokenize(1, '/foo/{id}');

        $this->assertSame(6, $tokenized->getTokenCount());
        $this->assertTrue($tokenized->getTokens()[5]->equals(Token::createPlaceholder('{id}')));
    }

    public function testShouldThrowOnTokenizerNotFound(): void
    {
        $tokenizer = new Tokenizer([]);

        $this->expectException(TokenizerNotFoundException::class);
        $tokenizer->tokenize(1, '/foo/{id}');
    }
}
