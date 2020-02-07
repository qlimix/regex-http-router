<?php declare(strict_types=1);

namespace Qlimix\Router\Match;

use Qlimix\Router\Tokenize\Tokenized;

final class MatcherIteratorFactory implements IteratorFactory
{
    /**
     * @inheritDoc
     */
    public function create(Tokenized $tokenized, array $tokenizeds): Iterator
    {
        return new Iterator(new Matcher($tokenized, $tokenizeds));
    }
}
