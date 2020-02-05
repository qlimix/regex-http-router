<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Regex;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\Regex\Exception\RegistryException;
use Qlimix\Router\Regex\PlaceHolder;
use Qlimix\Router\Regex\Registry;

final class RegistryTest extends TestCase
{
    public function testShouldRegister(): void
    {
        $placeHolderName = 'id';
        $regex = '([\d]+)';
        $placeHolder = new PlaceHolder($placeHolderName, $regex);

        $registry = new Registry();
        $registry->add($placeHolder);

        $fromRegistry = $registry->get($placeHolderName);

        $this->assertSame($placeHolderName, $fromRegistry->getPlaceHolder());
        $this->assertSame($regex, $fromRegistry->getRegex());
    }

    public function testShouldThrowOnNotFound(): void
    {
        $registry = new Registry();

        $this->expectException(RegistryException::class);
        $registry->get('foo');
    }
}
