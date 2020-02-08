<?php declare(strict_types=1);

namespace Qlimix\Http\Router\Regex;

use Qlimix\Http\Router\Regex\Translator\Exception\TranslatorException;
use Qlimix\Http\Router\Regex\Translator\TranslatorInterface;
use Qlimix\Http\Router\Tokenize\Token;
use Throwable;

final class Translator
{
    /** @var TranslatorInterface[] */
    private array $translators;

    /**
     * @param TranslatorInterface[] $translators
     */
    public function __construct(array $translators)
    {
        $this->translators = $translators;
    }

    /**
     * @param Token[] $tokens
     *
     * @throws TranslatorException
     */
    public function translate(array $tokens, ?int $id): string
    {
        try {
            $result = '';
            foreach ($tokens as $token) {
                foreach ($this->translators as $translator) {
                    if ($translator->can($token, $id)) {
                        $result .= $translator->translate($token, $id);
                        continue 2;
                    }
                }

                $result .= $token->getToken();
            }
        } catch (Throwable $exception) {
            throw new TranslatorException('Failed to translate tokens', 0, $exception);
        }

        return $result;
    }
}
