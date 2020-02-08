<?php declare(strict_types=1);

namespace Qlimix\Http\Router\Match;

use Qlimix\Http\Router\Tokenize\Tokenized;

interface IteratorFactory
{
    /**
     * @param Tokenized[] $tokenizeds
     */
    public function create(Tokenized $tokenized, array $tokenizeds): Iterator;
}
