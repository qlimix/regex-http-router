<?php declare(strict_types=1);

namespace Qlimix\Router\Regex;

final class PlaceHolder
{
    private string $name;

    private string $regex;

    public function __construct(string $name, string $regex)
    {
        $this->name = $name;
        $this->regex = $regex;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRegex(): string
    {
        return $this->regex;
    }
}
