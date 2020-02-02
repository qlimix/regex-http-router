<?php declare(strict_types=1);

namespace Qlimix\Router\Match;

final class Match
{
    private Tokens $tokens;

    /** @var Match[] */
    private array $children = [];

    public function __construct(Tokens $matchedTokens)
    {
        $this->tokens = $matchedTokens;
    }

    public function append(Tokens $tokens): self
    {
        foreach ($this->children as $child) {
            if ($child->getTokens()->equals($tokens)) {
                return $child;
            }
        }

        $match = new Match($tokens);
        $this->children[] = $match;

        return $match;
    }

    public function getTokens(): Tokens
    {
        return $this->tokens;
    }

    /**
     * @return Match[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public static function createRoot(): self
    {
        return new self(new Tokens([], null));
    }
}
