<?php declare(strict_types=1);

namespace Qlimix\Http\Router\Regex;

use Qlimix\Http\Router\Regex\Exception\InvalidRegexException;
use Qlimix\Http\Router\Regex\Exception\NoMatchFoundException;
use Throwable;
use function is_numeric;
use function preg_match;

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
        try {
            $result = preg_match('~'.$this->regex.'~', $toMatch, $matches);
        } catch (Throwable $exception) {
            throw new InvalidRegexException('Invalid regex', 0, $exception);
        }

        if ($result === 0) {
            throw new NoMatchFoundException('No matches found in the regex');
        }

        return $this->build($matches);
    }

    /**
     * @param string[] $matches
     */
    private function build(array $matches): Result
    {
        $id = null;
        $parameters = [];
        foreach ($matches as $index => $match) {
            if (!is_numeric($index)) {
                $id = $match;
                break;
            }

            if ($index === 0 || $match === '') {
                continue;
            }

            $parameters[] = $match;
        }

        return new Result((int) $id, $parameters);
    }
}
