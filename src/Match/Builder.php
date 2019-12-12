<?php declare(strict_types=1);

namespace Qlimix\Router\Match;

use Qlimix\Router\Tokenize\Tokenized;

final class Builder
{
    /**
     * @param Tokenized[] $tokenizeds
     */
    public function build(array $tokenizeds): Match
    {
        $build = Match::createRoot();
        foreach ($tokenizeds as $index => $tokenized) {
            $list = $tokenizeds;
            unset($list[$index]);

            $matcher = new Matcher($tokenized, $list);
            $iterator = new Iterator($matcher);
            $iterator->iterate($build);
        }

        return $build;
    }
}
