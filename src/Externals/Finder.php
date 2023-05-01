<?php
declare(strict_types=1);

namespace Eightfold\Amos\Externals;

use Symfony\Component\Finder\Finder as SymfonyFinder;

use Eightfold\Amos\Site;

class Finder
{
    /**
     * @return string[]
     */
    public static function allMetaPaths(Site $site): array
    {
        $iterator = (new SymfonyFinder())->files()->name('meta.json')
            ->in($site->publicRoot());

        $array = iterator_to_array($iterator);

        return array_keys($array);
    }
}
