<?php declare(strict_types=1);

namespace Qlimix\Router\Match;

use Qlimix\Router\Route;
use Qlimix\Router\Tokenize\Tokenizer;

final class MatchBuilder
{
    private Builder $builder;

    private Tokenizer $tokenizer;

    public function __construct(Builder $builder, Tokenizer $tokenizer)
    {
        $this->builder = $builder;
        $this->tokenizer = $tokenizer;
    }

    /**
     * @param Route[] $routes
     */
    public function build(array $routes): Match
    {
        $tokenizedRoutes = [];
        foreach ($routes as $index => $route) {
            $tokenizedRoutes[] = $this->tokenizer->tokenize($index, $route->getPath());
        }

        return $this->builder->build($tokenizedRoutes);
    }
}
