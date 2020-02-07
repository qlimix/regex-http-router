<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Match;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\Match\Builder;
use Qlimix\Router\Match\Exception\BuilderException;
use Qlimix\Router\Match\MatcherIteratorFactory;
use Qlimix\Router\Tokenize\Token;
use Qlimix\Router\Tokenize\Tokenized;

final class BuilderTest extends TestCase
{
    private MatcherIteratorFactory $iteratorFactory;

    protected function setUp(): void
    {
        $this->iteratorFactory = new MatcherIteratorFactory();
    }

    public function testShouldBuild(): void
    {
        $tokens = [
            Token::createChar('/'),
            Token::createChar('b'),
            Token::createChar('a'),
            Token::createChar('r'),
        ];

        $parsedRoute = new Tokenized(1, $tokens);

        $tokens2 = [
            Token::createChar('/'),
            Token::createChar('f'),
            Token::createChar('o'),
            Token::createChar('o'),
        ];

        $parsedRoute2 = new Tokenized(2, $tokens2);

        $parsedRoutes = [
            $parsedRoute,
            $parsedRoute2,
        ];

        $builder = new Builder($this->iteratorFactory);
        $build = $builder->build($parsedRoutes);
        $rootChildren = $build->getChildren();

        $this->assertCount(1, $rootChildren);
        $this->assertCount(2, $rootChildren[0]->getChildren());
        $this->assertCount(0, $rootChildren[0]->getChildren()[0]->getChildren());
        $this->assertCount(0, $rootChildren[0]->getChildren()[1]->getChildren());
    }

    public function testShouldBuild2(): void
    {
        $tokens = [
            Token::createChar('/'),
            Token::createChar('f'),
            Token::createChar('a'),
            Token::createChar('r'),
        ];

        $parsedRoute = new Tokenized(1, $tokens);

        $tokens2 = [
            Token::createChar('/'),
            Token::createChar('f'),
            Token::createChar('a'),
            Token::createChar('o'),
        ];

        $parsedRoute2 = new Tokenized(2, $tokens2);

        $tokens3 = [
            Token::createChar('/'),
            Token::createChar('b'),
            Token::createChar('a'),
            Token::createChar('r'),
        ];

        $parsedRoute3 = new Tokenized(3, $tokens3);

        $parsedRoutes = [
            $parsedRoute,
            $parsedRoute2,
            $parsedRoute3,
        ];

        $builder = new Builder($this->iteratorFactory);
        $build = $builder->build($parsedRoutes);
        $rootChildren = $build->getChildren();

        $this->assertCount(1, $rootChildren);
        $this->assertCount(2, $rootChildren[0]->getChildren());
        $this->assertCount(2, $rootChildren[0]->getChildren()[0]->getChildren());
    }

    public function testShouldShouldThrowOnIteratorException(): void
    {
        $tokens = [
            Token::createChar('/'),
        ];

        $parsedRoute = new Tokenized(1, $tokens);

        $tokens2 = [
            Token::createChar('/'),
        ];

        $parsedRoute2 = new Tokenized(1, $tokens2);

        $parsedRoutes = [
            $parsedRoute,
            $parsedRoute2,
        ];

        $this->expectException(BuilderException::class);
        $builder = new Builder($this->iteratorFactory);
        $builder->build($parsedRoutes);
    }
}
