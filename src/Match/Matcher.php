<?php declare(strict_types=1);

namespace Qlimix\Http\Router\Match;

use Qlimix\Http\Router\Match\Exception\LastMatchException;
use Qlimix\Http\Router\Tokenize\Tokenized;
use function array_slice;
use function count;

final class Matcher implements MatcherInterface
{
    private Tokenized $tokenized;

    /** @var Tokenized[] */
    private array $tokenizeds;

    private int $pointer = 0;

    private bool $last = false;

    /**
     * @param Tokenized[] $tokenizeds
     */
    public function __construct(Tokenized $tokenized, array $tokenizeds)
    {
        $this->tokenized = $tokenized;
        $this->tokenizeds = $tokenizeds;
    }

    /**
     * @throws LastMatchException
     */
    public function match(): Tokens
    {
        if ($this->last) {
            throw new LastMatchException('Last match');
        }

        if (count($this->tokenizeds) === 0) {
            $this->last = true;

            return new Tokens(
                array_slice($this->tokenized->getTokens(), $this->pointer),
                $this->tokenized->getId()
            );
        }

        $matchedTokens = [];

        do {
            if ($this->pointer > $this->tokenized->getTokenCount()-1) {
                $this->last = true;

                return new Tokens($matchedTokens, $this->tokenized->getId());
            }

            if (count($this->tokenizeds) === 0) {
                $this->last = true;

                return new Tokens(
                    array_slice($this->tokenized->getTokens(), $this->pointer),
                    $this->tokenized->getId()
                );
            }

            $newList = $this->getMatchingTokensList();

            $listSize = count($this->tokenizeds);
            $newListSize = count($newList);

            if ($listSize === $newListSize) {
                $matchedTokens[] = $this->tokenized->getTokens()[$this->pointer];
                $this->pointer++;
            }

            $this->tokenizeds = $newList;
        } while ($listSize === $newListSize || count($matchedTokens) === 0);

        return new Tokens($matchedTokens, null);
    }

    /**
     * @return Tokenized[]
     */
    private function getMatchingTokensList(): array
    {
        $newList = [];
        foreach ($this->tokenizeds as $tokenized) {
            if ($this->pointer > $tokenized->getTokenCount()-1) {
                continue;
            }

            if (!$this->tokenized->getTokens()[$this->pointer]->equals($tokenized->getTokens()[$this->pointer])) {
                continue;
            }

            $newList[] = $tokenized;
        }

        return $newList;
    }
}
