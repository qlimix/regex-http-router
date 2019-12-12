<?php declare(strict_types=1);

namespace Qlimix\Router\Regex;

final class PlaceHolder
{
    private string $placeHolder;

    private string $regex;

    public function __construct(string $placeHolder, string $regex)
    {
        $this->placeHolder = $placeHolder;
        $this->regex = $regex;
    }

    public function getPlaceHolder(): string
    {
        return $this->placeHolder;
    }

    public function getRegex(): string
    {
        return $this->regex;
    }
}
