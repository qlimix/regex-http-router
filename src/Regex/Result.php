<?php declare(strict_types=1);

namespace Qlimix\Http\Router\Regex;

final class Result
{
    private int $id;

    /** @var string[] */
    private array $parameters;

    /**
     * @param string[] $parameters
     */
    public function __construct(int $id, array $parameters)
    {
        $this->id = $id;
        $this->parameters = $parameters;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
