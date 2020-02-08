<?php declare(strict_types=1);

namespace Qlimix\Http\Router\Tokenize;

use Qlimix\Http\Router\Tokenize\Exception\FailedToTokenizeException;
use function strlen;

final class PlaceHolderTokenizer implements TokenizerInterface
{
    private const PLACEHOLDER_START = '{';
    private const PLACEHOLDER_END = '}';

    public function canTokenize(string $token): bool
    {
        return $token === self::PLACEHOLDER_START;
    }

    /**
     * @inheritDoc
     */
    public function tokenize(string $value, int $pointer): Token
    {
        $token = '';

        $length = strlen($value);
        do {
            $char = $value[$pointer];
            $token .= $char;
            $pointer++;
            if ($char !== self::PLACEHOLDER_END && $pointer >= $length) {
                throw new FailedToTokenizeException('Unclosed placeholder');
            }
        } while ($char !== self::PLACEHOLDER_END);

        return Token::createPlaceholder($token);
    }
}
