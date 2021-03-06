<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Regex;

use PHPUnit\Framework\TestCase;
use Qlimix\Http\Router\Regex\Exception\RegistryException;
use Qlimix\Http\Router\Regex\PlaceHolder;
use Qlimix\Http\Router\Regex\Registry;

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

        $this->assertSame($placeHolderName, $fromRegistry->getName());
        $this->assertSame($regex, $fromRegistry->getRegex());
    }

    public function testShouldThrowOnNotFound(): void
    {
        $registry = new Registry();

        $this->expectException(RegistryException::class);
        $registry->get('foo');
    }
}
