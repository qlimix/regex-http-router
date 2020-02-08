<?php declare(strict_types=1);

namespace Qlimix\Http\Router\Regex\Translator;

use Qlimix\Http\Router\Regex\Translator\Exception\TranslatorException;
use Qlimix\Http\Router\Tokenize\Token;

interface TranslatorInterface
{
    public function can(Token $token, ?int $id): bool;

    /**
     * @throws TranslatorException
     */
    public function translate(Token $token, ?int $id): string;
}
