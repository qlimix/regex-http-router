<?php declare(strict_types=1);

namespace Qlimix\Router\Tokenize;

final class TokenType
{
    private const TYPE_CHAR = 'char';
    private const TYPE_PLACEHOLDER = 'placeholder';

    private string $type;

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public function isChar(): bool
    {
        return $this->type === self::TYPE_CHAR;
    }

    public function isPlaceHolder(): bool
    {
        return $this->type === self::TYPE_PLACEHOLDER;
    }

    public static function createChar(): self
    {
        return new self(self::TYPE_CHAR);
    }

    public static function createPlaceHolder(): self
    {
        return new self(self::TYPE_PLACEHOLDER);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function equals(self $type): bool
    {
        return $type->getType() === $this->type;
    }
}
