<?php declare(strict_types=1);

namespace Qlimix\Router\Tokenize;

use Qlimix\Router\Tokenize\Exception\FailedToTokenizeException;
use Qlimix\Router\Tokenize\Exception\TokenizerNotFoundException;

final class Tokenizer
{
    /** @var TokenizerInterface[] */
    private array $tokenizers;

    /**
     * @param TokenizerInterface[] $tokenizers
     */
    public function __construct(array $tokenizers)
    {
        $this->tokenizers = $tokenizers;
    }

    /**
     * @throws FailedToTokenizeException
     * @throws TokenizerNotFoundException
     */
    public function tokenize(int $id, string $toTokenize): Tokenized
    {
        $pointer = 0;
        $length = strlen($toTokenize);

        $tokens = [];

        while ($length > $pointer) {
            foreach ($this->tokenizers as $tokenizer) {
                if ($tokenizer->canTokenize($toTokenize[$pointer])) {
                    $token = $tokenizer->tokenize($toTokenize, $pointer);
                    $tokens[] = $token;
                    $pointer += $token->getLength();
                    continue 2;
                }
            }

            throw new TokenizerNotFoundException('No tokenizer');
        }

        return new Tokenized($id, $tokens);
    }
}
