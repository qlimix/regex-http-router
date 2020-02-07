<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Regex;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\Regex\Exception\PlaceHolderMatchFailedException;
use Qlimix\Router\Regex\Exception\PlaceHolderNotFoundException;
use Qlimix\Router\Regex\PlaceHolder;
use Qlimix\Router\Regex\PlaceHolderMatcher;
use Qlimix\Router\Regex\Registry;
use Qlimix\Router\Tokenize\CharTokenizer;
use Qlimix\Router\Tokenize\PlaceHolderTokenizer;
use Qlimix\Router\Tokenize\Tokenizer;

final class PlaceHolderMatcherTest extends TestCase
{
    public function testShouldMatch(): void
    {
        $placeHolderName = 'id';
        $regex = '([\d]+)';
        $placeHolder = new PlaceHolder($placeHolderName, $regex);

        $tokenizer = new Tokenizer([
            new PlaceHolderTokenizer(),
            new CharTokenizer(),
        ]);

        $registry = new Registry();

        $placeHolderMatcher = new PlaceHolderMatcher($tokenizer, $registry);

        $result = $placeHolderMatcher->match('/foo/{id}', [$placeHolder]);

        $this->assertSame($placeHolderName, $result[0]->getName());
        $this->assertSame($regex, $result[0]->getRegex());
    }

    public function testShouldMatchWithPlaceHolderFromRegistry(): void
    {
        $placeHolderName = 'id';
        $regex = '([\d]+)';
        $placeHolder = new PlaceHolder($placeHolderName, $regex);

        $tokenizer = new Tokenizer([
            new PlaceHolderTokenizer(),
            new CharTokenizer(),
        ]);

        $registry = new Registry();
        $registry->add($placeHolder);

        $placeHolderMatcher = new PlaceHolderMatcher($tokenizer, $registry);

        $result = $placeHolderMatcher->match('/foo/{id}', []);

        $this->assertSame($placeHolderName, $result[0]->getName());
        $this->assertSame($regex, $result[0]->getRegex());
    }

    public function testShouldThrowOnFailingToTokenize(): void
    {
        $tokenizer = new Tokenizer([]);

        $registry = new Registry();

        $placeHolderMatcher = new PlaceHolderMatcher($tokenizer, $registry);

        $this->expectException(PlaceHolderMatchFailedException::class);
        $placeHolderMatcher->match('/foo/{id}', []);
    }

    public function testShouldThrowOnNoPlaceHolderFound(): void
    {
        $tokenizer = new Tokenizer([
            new PlaceHolderTokenizer(),
            new CharTokenizer(),
        ]);

        $registry = new Registry();

        $placeHolderMatcher = new PlaceHolderMatcher($tokenizer, $registry);

        $this->expectException(PlaceHolderNotFoundException::class);
        $placeHolderMatcher->match('/foo/{id}', []);
    }
}
