<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Regex;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\Regex\Exception\InvalidRegexException;
use Qlimix\Router\Regex\Exception\NoMatchFoundException;
use Qlimix\Router\Regex\Matcher;

final class MatcherTest extends TestCase
{
    public function testShouldMatch(): void
    {
        $matcher = new Matcher(
            '^(?|\/f(?|oo(?|(*MARK:0)|\/(\d+)(*MARK:1))))$'
        );

        $result = $matcher->match('/foo/123');
        $this->assertSame($result->getId(), 1);
        $this->assertSame($result->getParameters()[0], '123');
    }

    public function testShouldThrowOnInvalidRegex(): void
    {
        $matcher = new Matcher('~');

        $this->expectException(InvalidRegexException::class);
        $matcher->match('/foo/123');
    }

    public function testShouldThrowOnNoMatch(): void
    {
        $matcher = new Matcher('fao');

        $this->expectException(NoMatchFoundException::class);
        $matcher->match('/foo/123');
    }
}
