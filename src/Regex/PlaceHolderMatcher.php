<?php declare(strict_types=1);

namespace Qlimix\Router\Regex;

use Qlimix\Router\Regex\Exception\PlaceHolderMatchFailedException;
use Qlimix\Router\Regex\Exception\PlaceHolderNotFoundException;
use Qlimix\Router\Tokenize\Tokenizer;
use Throwable;
use function preg_match;

final class PlaceHolderMatcher
{
    private const REGEX_PLACEHOLDER = '~{([a-zA-Z0-9]+)}~';

    private Tokenizer $tokenizer;

    private Registry $registry;

    public function __construct(Tokenizer $tokenizer, Registry $registry)
    {
        $this->tokenizer = $tokenizer;
        $this->registry = $registry;
    }

    /**
     * @param PlaceHolder[] $placeHolders
     *
     * @return PlaceHolder[]
     *
     * @throws PlaceHolderMatchFailedException
     * @throws PlaceHolderNotFoundException
     */
    public function match(string $toMatch, array $placeHolders): array
    {
        try {
            $tokenized = $this->tokenizer->tokenize(1, $toMatch);
        } catch (Throwable $exception) {
            throw new PlaceHolderMatchFailedException('Failed to tokenize', 0, $exception);
        }

        $hold = [];
        foreach ($tokenized->getTokens() as $token) {
            if (!$token->getType()->isPlaceHolder()) {
                continue;
            }

            preg_match(self::REGEX_PLACEHOLDER, $token->getToken(), $matches);
            foreach ($placeHolders as $placeHolder) {
                if ($placeHolder->getPlaceHolder() === $matches[1]) {
                    $hold[] = $placeHolder;
                    continue 2;
                }
            }

            try {
                $placeHolder = $this->registry->get($matches[1]);
                $hold[] = $placeHolder;
            } catch (Throwable $exception) {
                throw new PlaceHolderNotFoundException('Could not find placeholder', 0, $exception);
            }
        }

        return $hold;
    }
}
