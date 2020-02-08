<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Regex;

use PHPUnit\Framework\TestCase;
use Qlimix\Http\Router\Regex\Result;

final class ResultTest extends TestCase
{
    public function testShouldResult(): void
    {
        $id = 1;
        $parameters = [
            'foo',
            'bar',
        ];

        $result = new Result($id, $parameters);

        $this->assertSame($id, $result->getId());
        $this->assertSame($parameters, $result->getParameters());
    }
}
