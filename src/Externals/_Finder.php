<?php
declare(strict_types=1);

namespace Eightfold\Amos\Externals;

use Symfony\Component\Finder\Finder as SymfonyFinder;

use Eightfold\Amos\Site;

use function Eightfold\Amos\real_paths_for_public_meta_files;

/**
 * @deprecated
 */
class Finder
{
    /**
     * @return string[]
     */
    public static function allMetaPaths(Site $site): array
    {
        return real_paths_for_public_meta_files($site->contentRoot());
    }
}
