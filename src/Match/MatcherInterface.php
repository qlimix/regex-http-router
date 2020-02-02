<?php declare(strict_types=1);

namespace Qlimix\Router\Match;

use Qlimix\Router\Match\Exception\LastMatchException;

interface MatcherInterface
{
    /**
     * @throws LastMatchException
     */
    public function match(): Tokens;
}
