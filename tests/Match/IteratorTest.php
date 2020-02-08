<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Match;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Qlimix\Http\Router\Match\Exception\IteratorException;
use Qlimix\Http\Router\Match\Exception\LastMatchException;
use Qlimix\Http\Router\Match\Iterator;
use Qlimix\Http\Router\Match\Match;
use Qlimix\Http\Router\Match\MatcherInterface;
use Qlimix\Http\Router\Match\Tokens;
use Qlimix\Http\Router\Tokenize\Token;

final class IteratorTest extends TestCase
{
    private MockObject $matcher;

    private Iterator $iterator;

    protected function setUp(): void
    {
        $this->matcher = $this->createMock(MatcherInterface::class);
        $this->iterator = new Iterator($this->matcher);
    }

    public function testShouldIterate(): void
    {
        $this->matcher->expects($this->once())
            ->method('match')
            ->willReturn(new Tokens(
                [
                    Token::createChar('/'),
                    Token::createChar('f'),
                ],
                1
            ));

        $match = Match::createRoot();
        $this->iterator->iterate($match);

        $this->assertSame('/', $match->getChildren()[0]->getTokens()->getTokens()[0]->getToken());
        $this->assertSame('f', $match->getChildren()[0]->getTokens()->getTokens()[1]->getToken());
    }

    public function testShouldIterateRecursive(): void
    {
        $this->matcher->expects($this->exactly(2))
            ->method('match')
            ->willReturn(
                new Tokens(
                    [
                        Token::createChar('/'),
                        Token::createChar('f'),
                    ],
                    null
                ),
                new Tokens(
                    [
                        Token::createChar('o'),
                        Token::createChar('o'),
                    ],
                    2
                )
            );

        $match = Match::createRoot();
        $this->iterator->iterate($match);

        $this->assertSame('/', $match->getChildren()[0]->getTokens()->getTokens()[0]->getToken());
        $this->assertSame('f', $match->getChildren()[0]->getTokens()->getTokens()[1]->getToken());

        $this->assertSame(
            'o',
            $match->getChildren()[0]->getChildren()[0]->getTokens()->getTokens()[0]->getToken()
        );
        $this->assertSame(
            'o',
            $match->getChildren()[0]->getChildren()[0]->getTokens()->getTokens()[1]->getToken()
        );
    }

    public function testShouldReturnOnMatchException(): void
    {
        $return = new Tokens(
            [
                Token::createChar('/'),
                Token::createChar('f'),
            ],
            null
        );
        $throw = false;

        $this->matcher->expects($this->exactly(2))
            ->method('match')
            ->willReturnCallback(static function() use ($return, &$throw) {
                if (!$throw) {
                    $throw = true;
                    return $return;
                }

                throw new LastMatchException('whoops');
            });

        $match = Match::createRoot();

        $this->iterator->iterate($match);
    }

    public function testShouldThrowOnMatchAppendingException(): void
    {

        $match = Match::createRoot();
        $match->append(new Tokens(
            [
                Token::createChar('/'),
            ],
            1
        ));

        $this->matcher->expects($this->exactly(1))
            ->method('match')
            ->willReturn(
                new Tokens(
                    [
                        Token::createChar('/'),
                    ],
                    1
                ),
            );

        $this->expectException(IteratorException::class);
        $this->iterator->iterate($match);
    }
}
