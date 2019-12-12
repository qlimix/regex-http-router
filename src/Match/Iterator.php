<?php declare(strict_types=1);

namespace Qlimix\Router\Match;

use Exception;
use Qlimix\Router\Match\Exception\IteratorException;
use Qlimix\Router\Match\Exception\LastMatchException;

final class Iterator
{
    private Matcher $matcher;

    public function __construct(Matcher $matcher)
    {
        $this->matcher = $matcher;
    }

    /**
     * @throws Exception
     */
    public function iterate(Match $match): void
    {
        try {
            $tokens = $this->matcher->match();
        } catch (LastMatchException $exception) {
            return;
        }

        try {
            if (!$match->hasChild($tokens)) {
                $next = new Match($tokens, []);
                $match->addChild($next);
            } else {
                $next = $match->getChild($tokens);
                if ($tokens->getId() !== null && $next->getTokens()->getId() === null) {
                    $next->getTokens()->setId($tokens->getId());
                }
            }

            if ($tokens->getId() === null) {
                $this->iterate($next);
            }
        } catch (Exception $exception) {
            throw new IteratorException('Failed to build match result',0, $exception);
        }
    }
}
