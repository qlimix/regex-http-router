<?php declare(strict_types=1);

namespace Qlimix\Router\Tokenize;

use Qlimix\Router\Tokenize\Exception\FailedToTokenizeException;

interface TokenizerInterface
{
    public function canTokenize(string $token): bool;

    /**
     * @throws FailedToTokenizeException
     */
    public function tokenize(string $value, int $pointer): Token;
}
