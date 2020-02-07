<?php declare(strict_types=1);

namespace Qlimix\Router\Match;

use Qlimix\Router\Match\Exception\IteratorException;
use Qlimix\Router\Match\Exception\LastMatchException;
use Throwable;

final class Iterator
{
    private MatcherInterface $matcher;

    public function __construct(MatcherInterface $matcher)
    {
        $this->matcher = $matcher;
    }

    /**
     * @throws IteratorException
     */
    public function iterate(Match $match): void
    {
        try {
            $tokens = $this->matcher->match();
        } catch (LastMatchException $exception) {
            return;
        }

        try {
            $next = $match->append($tokens);
        } catch (Throwable $exception) {
            throw new IteratorException('Failed to append child', 0, $exception);
        }

        if ($tokens->getId() !== null) {
            return;
        }

        $this->iterate($next);
    }
}
