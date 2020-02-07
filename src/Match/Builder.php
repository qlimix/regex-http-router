<?php declare(strict_types=1);

namespace Qlimix\Router\Match;

use Qlimix\Router\Match\Exception\BuilderException;
use Qlimix\Router\Tokenize\Tokenized;
use Throwable;

final class Builder
{
    private IteratorFactory $iteratorFactory;

    public function __construct(IteratorFactory $iteratorFactory)
    {
        $this->iteratorFactory = $iteratorFactory;
    }

    /**
     * @param Tokenized[] $tokenizeds
     *
     * @throws BuilderException
     */
    public function build(array $tokenizeds): Match
    {
        $build = Match::createRoot();
        foreach ($tokenizeds as $index => $tokenized) {
            $list = $tokenizeds;
            unset($list[$index]);

            $iterator = $this->iteratorFactory->create($tokenized, $list);
            try {
                $iterator->iterate($build);
            } catch (Throwable $exception) {
                throw new BuilderException('Failed to iterate', 0, $exception);
            }
        }

        return $build;
    }
}
