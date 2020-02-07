<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Regex;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\Regex\PlaceHolder;

final class PlaceHolderTest extends TestCase
{
    public function testShouldHoldData(): void
    {
        $foobar = 'foobar';
        $regex = 'regex';
        $placeHolder = new PlaceHolder($foobar, $regex);

        $this->assertSame($foobar, $placeHolder->getName());
        $this->assertSame($regex, $placeHolder->getRegex());
    }
}
