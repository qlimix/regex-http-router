<?php declare(strict_types=1);

namespace Qlimix\Http\Router\Regex\Translator;

use Qlimix\Http\Router\Container;
use Qlimix\Http\Router\Regex\Translator\Exception\TranslatorException;
use Qlimix\Http\Router\Tokenize\Token;
use Throwable;
use function preg_match;

final class PlaceHolderTranslator implements TranslatorInterface
{
    private const REGEX_PLACEHOLDER = '~{([a-zA-Z0-9]+)}~';

    private Container $routeContainer;

    public function __construct(Container $routeContainer)
    {
        $this->routeContainer = $routeContainer;
    }

    public function can(Token $token, ?int $id): bool
    {
        return $token->getType()->isPlaceHolder() && $id !== null;
    }

    /**
     * @inheritDoc
     */
    public function translate(Token $token, ?int $id): string
    {
        preg_match(self::REGEX_PLACEHOLDER, $token->getToken(), $matches);

        try {
            $route = $this->routeContainer->get($id);
        } catch (Throwable $exception) {
            throw new TranslatorException('Failed to find route by id', 0, $exception);
        }

        foreach ($route->getPlaceHolders() as $placeHolder) {
            if ($placeHolder->getName() === $matches[1]) {
                return $placeHolder->getRegex();
            }
        }

        throw new TranslatorException('Failed to find placeholder');
    }
}
