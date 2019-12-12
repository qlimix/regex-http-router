<?php declare(strict_types=1);

namespace Qlimix\Router\Match;

use Exception;
use Qlimix\Router\Match\Exception\MatchException;

final class Match
{
    private Tokens $tokens;

    /** @var Match[] */
    private array $children;

    /**
     * @param Match[] $children
     */
    public function __construct(Tokens $matchedTokens, array $children)
    {
        $this->tokens = $matchedTokens;
        $this->children = $children;
    }

    public function addChild(Match $match): void
    {
        $this->children[] = $match;
    }

    public function hasChild(Tokens $matchedTokens): bool
    {
        foreach ($this->children as $child) {
            if ($child->getTokens()->equals($matchedTokens)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function getChild(Tokens $matchedTokens): self
    {
        foreach ($this->children as $child) {
            if ($child->getTokens()->equals($matchedTokens)) {
                return $child;
            }
        }

        throw new MatchException('No child found');
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
        return new self(new Tokens([], null), []);
    }
}
