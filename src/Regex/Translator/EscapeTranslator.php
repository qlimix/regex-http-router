<?php declare(strict_types=1);

namespace Qlimix\Router\Regex\Translator;

use Qlimix\Router\Tokenize\Token;

final class EscapeTranslator implements TranslatorInterface
{
    private const CHARS = ['/', '.', '+', '?', '-'];

    private const ESCAPE_CHAR = '\\';

    public function can(Token $token, ?int $id): bool
    {
        return $token->getType()->isChar() && in_array($token->getToken(), self::CHARS, true);
    }

    /**
     * @inheritDoc
     */
    public function translate(Token $token, ?int $id): string
    {
        return self::ESCAPE_CHAR.$token->getToken();
    }
}
