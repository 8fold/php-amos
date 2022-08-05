<?php
declare(strict_types=1);

namespace Eightfold\Amos\Contracts;

use Eightfold\XMLBuilder\Contracts\Buildable as BuildableBase;

use Eightfold\Amos\Site;

interface Buildable extends BuildableBase
{
    public static function create(Site $site): self;

    public function site(): Site;
}
