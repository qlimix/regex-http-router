<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Match;

use PHPUnit\Framework\TestCase;
use Qlimix\Http\Router\Match\Builder;
use Qlimix\Http\Router\Match\Exception\MatchBuilderException;
use Qlimix\Http\Router\Match\MatchBuilder;
use Qlimix\Http\Router\Match\MatcherIteratorFactory;
use Qlimix\Http\Router\Method;
use Qlimix\Http\Router\HttpRoute;
use Qlimix\Http\Router\Tokenize\Token;
use Qlimix\Http\Router\Tokenize\Tokenizer;
use Qlimix\Http\Router\Tokenize\TokenizerInterface;

final class MatchBuilderTest extends TestCase
{
    private MatcherIteratorFactory $iteratorFactory;

    protected function setUp(): void
    {
        $this->iteratorFactory = new MatcherIteratorFactory();
    }

    public function testShouldBuildMatch(): void
    {
        $tokenizer = $this->createMock(TokenizerInterface::class);
        $tokenizer->expects($this->exactly(4))
            ->method('canTokenize')
            ->willReturn(true);

        $tokenizer->expects($this->exactly(4))
            ->method('tokenize')
            ->willReturn(
                Token::createChar('g'),
                Token::createChar('e'),
                Token::createChar('t'),
                Token::createChar('/'),
            );

        $matchBuilder = new MatchBuilder(new Builder($this->iteratorFactory), new Tokenizer([$tokenizer]));

        $match = $matchBuilder->build([
            new HttpRoute(Method::createGet(), '/', 'foo', []),
        ]);

        $this->assertSame('g', $match->getChildren()[0]->getTokens()->getTokens()[0]->getToken());
        $this->assertSame('e', $match->getChildren()[0]->getTokens()->getTokens()[1]->getToken());
        $this->assertSame('t', $match->getChildren()[0]->getTokens()->getTokens()[2]->getToken());
        $this->assertSame('/', $match->getChildren()[0]->getTokens()->getTokens()[3]->getToken());
    }

    public function testShouldThrowOnNoTokenizerFound(): void
    {
        $tokenizer = $this->createMock(TokenizerInterface::class);
        $tokenizer->expects($this->once())
            ->method('canTokenize')
            ->willReturn(false);

        $matchBuilder = new MatchBuilder(new Builder($this->iteratorFactory), new Tokenizer([$tokenizer]));

        $this->expectException(MatchBuilderException::class);
        $matchBuilder->build([
            new HttpRoute(Method::createGet(), '/', 'foo', []),
        ]);
    }

    public function testShouldThrowOnBuilderException(): void
    {
        $tokenizer = $this->createMock(TokenizerInterface::class);
        $tokenizer->expects($this->exactly(8))
            ->method('canTokenize')
            ->willReturn(true);

        $tokenizer->expects($this->exactly(8))
            ->method('tokenize')
            ->willReturn(
                Token::createChar('g'),
                Token::createChar('e'),
                Token::createChar('t'),
                Token::createChar('/'),
                Token::createChar('g'),
                Token::createChar('e'),
                Token::createChar('t'),
                Token::createChar('/'),
            );

        $matchBuilder = new MatchBuilder(new Builder($this->iteratorFactory), new Tokenizer([$tokenizer]));

        $this->expectException(MatchBuilderException::class);
        $matchBuilder->build([
            new HttpRoute(Method::createGet(), '/', 'foo', []),
            new HttpRoute(Method::createGet(), '/', 'foo', []),
        ]);
    }
}
