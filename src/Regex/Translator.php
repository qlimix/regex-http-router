<?php declare(strict_types=1);

namespace Qlimix\Router\Regex;

use Qlimix\Router\Regex\Translator\Exception\TranslatorException;
use Qlimix\Router\Regex\Translator\TranslatorInterface;
use Qlimix\Router\Tokenize\Token;
use Throwable;

final class Translator
{
    /** @var TranslatorInterface[] */
    private array $translators;

    /**
     * @param TranslatorInterface[] $translators
     */
    public function __construct($translators)
    {
        $this->translators = $translators;
    }

    /**
     * @param Token[] $tokens
     *
     * @throws TranslatorException
     */
    public function escape(array $tokens, ?int $id): string
    {
        try {
            $result = '';
            foreach ($tokens as $token) {
                foreach ($this->translators as $translator) {
                    if ($translator->can($token, $id)) {
                        $result .= $translator->translate($token, $id);
                        break;
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
