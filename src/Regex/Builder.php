<?php declare(strict_types=1);

namespace Qlimix\Router\Regex;

use Qlimix\Router\Match\Match;
use function count;
use function implode;

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
            $regex[] = ')$';
            $build[] = implode('', $regex);
        }

        return implode('|', $build);
    }

    private function makeCaptureGroup(Match $match, ?int $id): string
    {
        $translated = $this->translator->translate($match->getTokens()->getTokens(), $match->getTokens()->getId());
        if (count($match->getChildren()) === 0) {
            if ($id !== null) {
                $build = [];
                $build[] = '(*MARK:'.$id.')';
                $build[] = $translated.'(*MARK:'.$match->getTokens()->getId().')';

                return implode('|', $build);
            }

            $matchRouteId = $match->getTokens()->getId();
            if ($matchRouteId !== null) {
                $translated .= '(*MARK:'.$match->getTokens()->getId().')';
            }

            return $translated;
        }

        $regex = [];

        if ($id !== null) {
            $regex[] = '(*MARK:'.$id.')|';
        }

        $regex[] = $translated;

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
