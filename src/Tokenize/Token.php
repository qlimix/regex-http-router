<?php declare(strict_types=1);

namespace Qlimix\Router\Tokenize;

final class Token
{
    private TokenType $type;
    private string $token;

    private function __construct(TokenType $type, string $token)
    {
        $this->type = $type;
        $this->token = $token;
    }

    public static function createChar(string $token): self
    {
        return new Token(TokenType::createChar(), $token);
    }

    public static function createPlaceholder(string $token): self
    {
        return new Token(TokenType::createPlaceHolder(), $token);
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getType(): TokenType
    {
        return $this->type;
    }

    public function getLength(): int
    {
        return strlen($this->token);
    }

    public function equals(Token $token): bool
    {
        return $token->getType()->equals($this->type) && $token->getToken() === $this->token;
    }
}
