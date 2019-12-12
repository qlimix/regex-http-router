<?php declare(strict_types=1);

namespace Qlimix\Router\Regex\Translator;

use Qlimix\Router\Regex\Translator\Exception\TranslatorException;
use Qlimix\Router\Tokenize\Token;

interface TranslatorInterface
{
    public function can(Token $token, ?int $id): bool;

    /**
     * @throws TranslatorException
     */
    public function translate(Token $token, ?int $id): string;
}
