<?php declare(strict_types=1);

namespace Qlimix\Router\Tokenize;

final class Tokenized
{
    private int $id;

    /** @var Token[] */
    private array $tokens;

    /**
     * @param Token[] $tokens
     */
    public function __construct(int $id, array $tokens)
    {
        $this->id = $id;
        $this->tokens = $tokens;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Token[]
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    public function getTokenCount(): int
    {
        return count($this->tokens);
    }
}
