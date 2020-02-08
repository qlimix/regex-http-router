<?php declare(strict_types=1);

namespace Qlimix\Http\Router\Match;

use Qlimix\Http\Router\Match\Exception\MatchException;
use Throwable;

final class Match
{
    private Tokens $tokens;

    /** @var Match[] */
    private array $children = [];

    public function __construct(Tokens $matchedTokens)
    {
        $this->tokens = $matchedTokens;
    }

    /**
     * @throws MatchException
     */
    public function append(Tokens $tokens): self
    {
        foreach ($this->children as $child) {
            if (!$child->getTokens()->equals($tokens)) {
                continue;
            }

            if ($tokens->getId() !== null) {
                try {
                    $child->tokens->promote($tokens->getId());
                } catch (Throwable $exception) {
                    throw new MatchException('Failed to promote tokens with id', 0, $exception);
                }
            }

            return $child;
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
