<?php declare(strict_types=1);

namespace Qlimix\Router\Match;

use Qlimix\Router\Match\Exception\LastMatchException;

final class Iterator
{
    private MatcherInterface $matcher;

    public function __construct(MatcherInterface $matcher)
    {
        $this->matcher = $matcher;
    }

    public function iterate(Match $match): void
    {
        try {
            $tokens = $this->matcher->match();
        } catch (LastMatchException $exception) {
            return;
        }

        $next = $match->append($tokens);

        if ($tokens->getId() !== null) {
            return;
        }

        $this->iterate($next);
    }
}
