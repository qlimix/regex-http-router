<?php declare(strict_types=1);

namespace Qlimix\Router\Tokenize;

final class CharTokenizer implements TokenizerInterface
{
    public function canTokenize(string $token): bool
    {
        return true;
    }

    public function tokenize(string $value, int $pointer): Token
    {
        return Token::createChar($value[$pointer]);
    }
}
