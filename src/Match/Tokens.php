<?php declare(strict_types=1);

namespace Qlimix\Router\Match;

use Qlimix\Router\Tokenize\Token;

final class Tokens
{
    /** @var Token[] */
    private array $tokens;

    private ?int $id;

    /**
     * @param Token[] $tokens
     */
    public function __construct(array $tokens, ?int $id)
    {
        $this->tokens = $tokens;
        $this->id = $id;
    }

    /**
     * @return Token[]
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function toString(): string
    {
        $string = '';
        foreach ($this->tokens as $token) {
            $string .= $token->getToken();
        }

        return $string;
    }

    public function equals(Tokens $tokens): bool
    {
        return $this->toString() === $tokens->toString();
    }
}
