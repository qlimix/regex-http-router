<?php declare(strict_types=1);

namespace Qlimix\Http\Router\Match;

use Qlimix\Http\Router\HttpRoute;
use Qlimix\Http\Router\Match\Exception\MatchBuilderException;
use Qlimix\Http\Router\Tokenize\Tokenizer;
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
     * @param HttpRoute[] $routes
     *
     * @throws MatchBuilderException
     */
    public function build(array $routes): Match
    {
        $tokenizedRoutes = [];
        foreach ($routes as $index => $route) {
            try {
                $tokenizedRoutes[] = $this->tokenizer->tokenize($index, $route->toString());
            } catch (Throwable $exception) {
                throw new MatchBuilderException('Failed to tokenize route', 0, $exception);
            }
        }

        try {
            return $this->builder->build($tokenizedRoutes);
        } catch (Throwable $exception) {
            throw new MatchBuilderException('Failed to build', 0, $exception);
        }
    }
}
