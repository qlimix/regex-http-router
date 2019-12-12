<?php declare(strict_types=1);

namespace Qlimix\Router\Regex;

use Qlimix\Router\Regex\Exception\InvalidRegexException;
use Qlimix\Router\Regex\Exception\NoMatchFoundException;

final class Matcher
{
    private string $regex;

    public function __construct(string $regex)
    {
        $this->regex = $regex;
    }

    /**
     * @throws InvalidRegexException
     * @throws NoMatchFoundException
     */
    public function match(string $toMatch): Result
    {
        $matches = [];
        $result = preg_match('~'.$this->regex.'~', $toMatch, $matches);

        if (!$result) {
            throw new InvalidRegexException('Invalid regex');
        }

        if ($result === 0){
            throw new NoMatchFoundException('No matches found in the regex');
        }

        $id = null;
        $parameters = [];
        foreach ($matches as $index => $match) {
            if (!is_numeric($index)) {
                $id = $match;
                break;
            }

            if ($index !== 0) {
                $parameters[] = $match;
            }
        }

        return new Result((int) $id, $parameters);
    }
}
