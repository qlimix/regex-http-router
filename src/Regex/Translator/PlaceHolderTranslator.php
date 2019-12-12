<?php declare(strict_types=1);

namespace Qlimix\Router\Regex\Translator;

use Qlimix\Router\Container;
use Qlimix\Router\Regex\Translator\Exception\TranslatorException;
use Qlimix\Router\Tokenize\Token;
use Throwable;

final class PlaceHolderTranslator implements TranslatorInterface
{
    private const REGEX_PLACEHOLDER = '~{([a-zA-Z0-9]+)}~';

    private Container $routeContainer;

    /**
     * @param Container $routeContainer
     */
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
        if (!$this->routeContainer->has($id)) {
            throw new TranslatorException('Couldn\'t find by id');
        }

        try {
            $route = $this->routeContainer->get($id);
        } catch (Throwable $exception) {
            throw new TranslatorException('Failed to find route by id');
        }

        foreach ($route->getPlaceHolders() as $placeHolder) {
            if ($placeHolder->getPlaceHolder() === $matches[1]) {
                return $placeHolder->getRegex();
            }
        }

        return '';
    }
}
