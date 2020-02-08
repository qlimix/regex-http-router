<?php declare(strict_types=1);

namespace Qlimix\Http\Router\Match;

use Qlimix\Http\Router\Match\Exception\LastMatchException;

interface MatcherInterface
{
    /**
     * @throws LastMatchException
     */
    public function match(): Tokens;
}
