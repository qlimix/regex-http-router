<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Regex\Translator;

use PHPUnit\Framework\TestCase;
use Qlimix\Http\Router\Container;
use Qlimix\Http\Router\Method;
use Qlimix\Http\Router\Regex\PlaceHolder;
use Qlimix\Http\Router\Regex\Translator\Exception\TranslatorException;
use Qlimix\Http\Router\Regex\Translator\PlaceHolderTranslator;
use Qlimix\Http\Router\HttpRoute;
use Qlimix\Http\Router\Tokenize\Token;

final class PlaceHolderTranslatorTest extends TestCase
{
    private Container $container;

    private PlaceHolderTranslator $placeholderTranslator;

    protected function setUp(): void
    {
        $this->container = new Container();
        $this->placeholderTranslator = new PlaceHolderTranslator($this->container);
    }

    public function testShouldEscape(): void
    {
        $token = Token::createPlaceholder('{id}');

        $regex = '([\d]+)';
        $this->container->add(
            new HttpRoute(Method::createGet(), '', '', [new PlaceHolder('id', $regex)])
        );

        $this->assertTrue($this->placeholderTranslator->can($token, 0));
        $this->assertSame('([\d]+)', $this->placeholderTranslator->translate($token, 0));
    }

    public function testShouldNotEscape(): void
    {
        $token = Token::createChar('a');

        $this->assertFalse($this->placeholderTranslator->can($token, 0));
        $this->assertFalse($this->placeholderTranslator->can($token, null));
    }

    public function testShouldThrowOnNotFindingRoute(): void
    {
        $token = Token::createPlaceholder('{id}');

        $regex = '([\d]+)';
        $this->container->add(
            new HttpRoute(Method::createGet(), '', '', [new PlaceHolder('foo', $regex)])
        );

        $this->assertTrue($this->placeholderTranslator->can($token, 1));

        $this->expectException(TranslatorException::class);
        $this->placeholderTranslator->translate($token, 1);
    }

    public function testShouldThrowOnNotFindingPlaceholder(): void
    {
        $token = Token::createPlaceholder('{id}');

        $regex = '([\d]+)';
        $this->container->add(
            new HttpRoute(Method::createGet(), '', '', [new PlaceHolder('foo', $regex)])
        );

        $this->assertTrue($this->placeholderTranslator->can($token, 0));

        $this->expectException(TranslatorException::class);
        $this->placeholderTranslator->translate($token, 0);
    }
}
