<?php declare(strict_types=1);

namespace Qlimix\Http\Router\Regex;

use Qlimix\Http\Router\Regex\Exception\RegistryException;

final class Registry
{
    /** @var PlaceHolder[] */
    private array $placeHolders = [];

    public function add(PlaceHolder $placeHolder): void
    {
        $this->placeHolders[] = $placeHolder;
    }

    /**
     * @throws RegistryException
     */
    public function get(string $search): PlaceHolder
    {
        foreach ($this->placeHolders as $placeHolder) {
            if ($placeHolder->getName() === $search) {
                return $placeHolder;
            }
        }

        throw new RegistryException('Couldn\'t find placeholder');
    }
}
