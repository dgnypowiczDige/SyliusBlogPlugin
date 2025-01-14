<?php

declare(strict_types=1);

namespace Odiseo\SyliusBlogPlugin\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class BlogExtension extends AbstractExtension
{
    private ?string $disqusShortname;

    public function __construct(
        ?string $disqusShortname
    ) {
        $this->disqusShortname = $disqusShortname;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('disqus_shortname', [$this, 'getDisqusShortname'])
        ];
    }

    public function getDisqusShortname(): ?string
    {
        return $this->disqusShortname;
    }

    public function getName(): string
    {
        return 'blog';
    }
}
