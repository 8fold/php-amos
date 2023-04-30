<?php
declare(strict_types=1);

namespace Eightfold\Amos\Implementations;

use Eightfold\Amos\Contracts\Buildable as BuildableContract;

use Eightfold\Amos\Site;

trait Buildable
{
    public static function create(Site $site): self
    {
        return new self($site);
    }

    final private function __construct(private Site $site)
    {
    }

    public function site(): Site
    {
        return $this->site;
    }

    public function __toString(): string
    {
        return $this->build();
    }
}
