<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Parser;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\Tokenize\CharTokenizer;
use Qlimix\Router\Tokenize\PlaceHolderTokenizer;
use Qlimix\Router\Tokenize\Tokenizer;

final class TokenizerTest extends TestCase
{
    private Tokenizer $tokenizer;

    protected function setUp(): void
    {
        $tokenizers = [
            new PlaceHolderTokenizer(),
            new CharTokenizer(),
        ];

        $this->tokenizer = new Tokenizer($tokenizers);
    }

    public function testShouldTokenize(): void
    {
        $parsed = $this->tokenizer->tokenize(1, '/foo/{id}/bar');

        $this->assertCount(10, $parsed->getTokens());
    }
}
