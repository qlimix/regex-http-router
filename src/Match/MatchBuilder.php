<?php declare(strict_types=1);

namespace Qlimix\Router\Match;

use Qlimix\Router\Match\Exception\MatchBuilderException;
use Qlimix\Router\Route;
use Qlimix\Router\Tokenize\Tokenizer;
use Throwable;

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
     *
     * @throws MatchBuilderException
     */
    public function build(array $routes): Match
    {
        $tokenizedRoutes = [];
        foreach ($routes as $index => $route) {
            try {
                $tokenizedRoutes[] = $this->tokenizer->tokenize($index, $route->getPath());
            } catch (Throwable $exception) {
                throw new MatchBuilderException('Failed to tokenize route', 0, $exception);
            }
        }

        return $this->builder->build($tokenizedRoutes);
    }
}
