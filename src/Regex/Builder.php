<?php declare(strict_types=1);

namespace Qlimix\Router\Regex;

use Qlimix\Router\Match\Match;

final class Builder
{
    private Translator $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function build(Match $match): string
    {
        $build = [];
        foreach ($match->getChildren() as $child) {
            $regex = [];
            $regex[] = '^(?|';
            $regex[] = $this->makeCaptureGroup($child, $match->getTokens()->getId());
            $regex[] .= ')$';
            $build[] = implode('', $regex);
        }

        return implode('|', $build);
    }

    private function makeCaptureGroup(Match $match, ?int $routeId = null): string
    {
        $route = $this->translator->escape($match->getTokens()->getTokens(), $routeId);
        if (count($match->getChildren()) === 0) {
            if ($routeId !== null) {
                $build = [];
                $build[] = '(*MARK:'.$routeId.')';
                $build[] = $route.'(*MARK:'.$match->getTokens()->getId().')';
                return implode('|', $build);
            }

            $matchRouteId = $match->getTokens()->getId();
            if ($matchRouteId !== null) {
                $route .= '(*MARK:'.$match->getTokens()->getId().')';
            }
            return $route;
        }

        $regex = [];
        $regex[] = $route;

        $regex[] = '(?|';
        $build = [];
        foreach ($match->getChildren() as $child) {
            $build[] = $this->makeCaptureGroup($child, $match->getTokens()->getId());
        }

        $regex[] = implode('|', $build);
        $regex[] = ')';

        return implode('', $regex);
    }
}
