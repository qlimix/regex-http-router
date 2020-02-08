<?php declare(strict_types=1);

namespace Qlimix\Tests\Router\Regex;

use PHPUnit\Framework\TestCase;
use Qlimix\Http\Router\Container;
use Qlimix\Http\Router\Method;
use Qlimix\Http\Router\Regex\PlaceHolder;
use Qlimix\Http\Router\Regex\Translator;
use Qlimix\Http\Router\Regex\Translator\EscapeTranslator;
use Qlimix\Http\Router\Regex\Translator\Exception\TranslatorException;
use Qlimix\Http\Router\Regex\Translator\PlaceHolderTranslator;
use Qlimix\Http\Router\Regex\Translator\TranslatorInterface;
use Qlimix\Http\Router\HttpRoute;
use Qlimix\Http\Router\Tokenize\Token;

final class TranslatorTest extends TestCase
{
    public function testShouldTranslate(): void
    {
        $container = new Container();
        $container->add(
            new HttpRoute(Method::createGet(), '', '', [new PlaceHolder('id', '([\d]+)')])
        );

        $translator = new Translator([
            new PlaceHolderTranslator($container),
            new EscapeTranslator(),
        ]);

        $translated = $translator->translate(
            [
                Token::createChar('/'),
                Token::createPlaceholder('{id}'),
            ],
            0
        );

        $this->assertSame('\/([\d]+)', $translated);
    }

    public function testShouldIgnoreNoneSpecialChars(): void
    {
        $translator = new Translator([]);

        $translated = $translator->translate(
            [
                Token::createChar('a'),
                Token::createChar('b'),
            ],
            0
        );

        $this->assertSame('ab', $translated);
    }

    public function testShouldThrowOnTranslatorException(): void
    {
        $translatorMock = $this->createMock(TranslatorInterface::class);

        $translatorMock->expects($this->once())
            ->method('can')
            ->willReturn(true);

        $translatorMock->expects($this->once())
            ->method('translate')
            ->willThrowException(new TranslatorException());

        $translator = new Translator([$translatorMock]);

        $this->expectException(TranslatorException::class);
        $translator->translate(
            [
                Token::createChar('/'),
                Token::createPlaceholder('{id}'),
            ],
            0
        );
    }
}
