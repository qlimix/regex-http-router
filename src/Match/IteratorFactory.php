<?php declare(strict_types=1);

namespace Qlimix\Router\Match;

use Qlimix\Router\Tokenize\Tokenized;

interface IteratorFactory
{
    /**
     * @param Tokenized[] $tokenizeds
     */
    public function create(Tokenized $tokenized, array $tokenizeds): Iterator;
}
