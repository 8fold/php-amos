<?php
declare(strict_types=1);

namespace Eightfold\Amos\RealPaths;

use Eightfold\Amos\RealPaths\ForFilesInterface;

use Eightfold\Amos\RealPaths\ForFilesTrait;

final class ForContentFiles implements ForFilesInterface
{
    use ForFilesTrait;

    public static function filename(): string
    {
        return '/content.md';
    }
}
