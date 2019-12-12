<?php declare(strict_types=1);

namespace Qlimix\Router;

use Qlimix\Router\Regex\PlaceHolder;

final class Route
{
    private Method $method;

    private string $path;

    private string $handler;

    /** @var PlaceHolder[] */
    private array $placeHolders;

    public function __construct(
        Method $method,
        string $path,
        string $handler,
        array $placeHolders
    ) {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
        $this->placeHolders = $placeHolders;
    }

    public function getMethod(): Method
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getHandler(): string
    {
        return $this->handler;
    }

    /**
     * @return PlaceHolder[]
     */
    public function getPlaceHolders(): array
    {
        return $this->placeHolders;
    }

    public function toString(): string
    {
        return $this->path;
    }
}
