<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Match;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\Match\Builder;
use Qlimix\Router\Match\Exception\MatchBuilderException;
use Qlimix\Router\Match\MatchBuilder;
use Qlimix\Router\Method;
use Qlimix\Router\Route;
use Qlimix\Router\Tokenize\Token;
use Qlimix\Router\Tokenize\Tokenizer;
use Qlimix\Router\Tokenize\TokenizerInterface;

final class MatchBuilderTest extends TestCase
{
    public function testShouldBuildMatch(): void
    {
        $tokenizer = $this->createMock(TokenizerInterface::class);
        $tokenizer->expects($this->once())
            ->method('canTokenize')
            ->willReturn(true);

        $tokenizer->expects($this->once())
            ->method('tokenize')
            ->willReturn(Token::createChar('/'));

        $matchBuilder = new MatchBuilder(new Builder(), new Tokenizer([$tokenizer]));

        $matchBuilder->build([
            new Route(Method::createGet(), '/', 'foo', []),
        ]);
    }

    public function testShouldThrowExceptionOnNoTokenizerFound(): void
    {
        $tokenizer = $this->createMock(TokenizerInterface::class);
        $tokenizer->expects($this->once())
            ->method('canTokenize')
            ->willReturn(false);

        $matchBuilder = new MatchBuilder(new Builder(), new Tokenizer([$tokenizer]));

        $this->expectException(MatchBuilderException::class);
        $matchBuilder->build([
            new Route(Method::createGet(), '/', 'foo', []),
        ]);
    }
}
