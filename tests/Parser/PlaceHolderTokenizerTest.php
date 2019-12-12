<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Parser;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\Tokenize\PlaceHolderTokenizer;

final class PlaceHolderTokenizerTest extends TestCase
{
    public function testShouldTokenize(): void
    {
        $tokenizer = new PlaceHolderTokenizer();

        $pointer = 5;
        $token = '/foo/{id}/bar';

        $this->assertTrue($tokenizer->canTokenize($token[$pointer]));

        $this->assertSame('{id}', $tokenizer->tokenize($token, $pointer)->getToken());
    }
}
