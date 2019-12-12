<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Regex;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\Regex\Matcher;

final class MatcherTest extends TestCase
{
    public function testShouldMatch(): void
    {
        $matcher = new Matcher(
            '^(?|\/f(?|oo(?|(*MARK:0)|\/(\d+)(*MARK:4))|io(*MARK:1)|ao(*MARK:2)))$|^(?|foo(*MARK:3))$'
        );

        $result = $matcher->match('/foo/123');
        $this->assertSame($result->getId(), 4);
        $this->assertSame($result->getParameters()[0], '123');
    }
}
