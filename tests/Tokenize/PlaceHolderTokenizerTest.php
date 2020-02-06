<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Tokenize;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\Tokenize\Exception\FailedToTokenizeException;
use Qlimix\Router\Tokenize\PlaceHolderTokenizer;
use Qlimix\Router\Tokenize\Token;

final class PlaceHolderTokenizerTest extends TestCase
{
    public function testShouldTokenize(): void
    {
        $placeHolderHolderTokenizer = new PlaceHolderTokenizer();

        $this->assertTrue($placeHolderHolderTokenizer->canTokenize('{'));
        $this->assertTrue(
            Token::createPlaceholder('{id}')
                ->equals($placeHolderHolderTokenizer->tokenize('/foo/{id}', 5))
        );
    }

    public function testShouldFailOnUnclosedBracket(): void
    {
        $placeHolderHolderTokenizer = new PlaceHolderTokenizer();

        $this->assertTrue($placeHolderHolderTokenizer->canTokenize('{'));

        $this->expectException(FailedToTokenizeException::class);
        $placeHolderHolderTokenizer->tokenize('/foo/{id', 5);
    }
}
