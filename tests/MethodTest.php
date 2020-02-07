<?php declare(strict_types=1);

namespace Qlimix\Tests\Router;

use PHPUnit\Framework\TestCase;
use Qlimix\Router\Method;

final class MethodTest extends TestCase
{
    public function testShouldMethodGet(): void
    {
        $method = Method::createGet();

        $this->assertTrue($method->isGet());
        $this->assertSame('get', $method->getMethod());
        $this->assertTrue($method->equals(Method::createGet()));

        $this->assertFalse($method->isPost());
        $this->assertFalse($method->equals(Method::createPost()));
    }

    public function testShouldMethodPost(): void
    {
        $method = Method::createPost();

        $this->assertTrue($method->isPost());
        $this->assertSame('post', $method->getMethod());
        $this->assertTrue($method->equals(Method::createPost()));

        $this->assertFalse($method->isGet());
        $this->assertFalse($method->equals(Method::createGet()));
    }

    public function testShouldMethodPut(): void
    {
        $method = Method::createPut();

        $this->assertTrue($method->isPut());
        $this->assertSame('put', $method->getMethod());
        $this->assertTrue($method->equals(Method::createPut()));

        $this->assertFalse($method->isGet());
        $this->assertFalse($method->equals(Method::createGet()));
    }

    public function testShouldMethodPatch(): void
    {
        $method = Method::createPatch();

        $this->assertTrue($method->isPatch());
        $this->assertSame('patch', $method->getMethod());
        $this->assertTrue($method->equals(Method::createPatch()));

        $this->assertFalse($method->isGet());
        $this->assertFalse($method->equals(Method::createGet()));
    }

    public function testShouldMethodOptions(): void
    {
        $method = Method::createOptions();

        $this->assertTrue($method->isOptions());
        $this->assertSame('options', $method->getMethod());
        $this->assertTrue($method->equals(Method::createOptions()));

        $this->assertFalse($method->isGet());
        $this->assertFalse($method->equals(Method::createGet()));
    }

    public function testShouldMethodDelete(): void
    {
        $method = Method::createDelete();

        $this->assertTrue($method->isDelete());
        $this->assertSame('delete', $method->getMethod());
        $this->assertTrue($method->equals(Method::createDelete()));

        $this->assertFalse($method->isGet());
        $this->assertFalse($method->equals(Method::createGet()));
    }

    public function testShouldMethodHead(): void
    {
        $method = Method::createHead();

        $this->assertTrue($method->isHead());
        $this->assertSame('head', $method->getMethod());
        $this->assertTrue($method->equals(Method::createHead()));

        $this->assertFalse($method->isGet());
        $this->assertFalse($method->equals(Method::createGet()));
    }
}
