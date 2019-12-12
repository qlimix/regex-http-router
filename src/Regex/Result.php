<?php declare(strict_types=1);

namespace Qlimix\Router\Regex;

final class Result
{
    private int $id;

    /** @var string[] */
    private array $parameters;

    /**
     * @param string[] $parameters
     */
    public function __construct(int $id, $parameters)
    {
        $this->id = $id;
        $this->parameters = $parameters;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
