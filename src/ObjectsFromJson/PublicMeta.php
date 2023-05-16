<?php
declare(strict_types=1);

namespace Eightfold\Amos\ObjectsFromJson;

use StdClass;
use DateTime;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\PlainText\PublicMeta as PlainTextPublicMeta;

final class PublicMeta
{
    private StdClass $object;

    public static function inRoot(Root $root, string $at = ''): self
    {
        return new self(
            PlainTextPublicMeta::inRoot($root, $at)
        );
    }

    private function __construct(
        private readonly PlainTextPublicMeta $publicMeta
    ) {
    }

    public function hasProperty(string $property): bool
    {
        $object = $this->object();
        return property_exists($object, $property);
    }

    private function object(): StdClass
    {
        if (isset($this->object) === false) {
            $json = $this->publicMeta->toString();

            $object = json_decode($json);
            if (
                is_object($object) === false or
                is_a($object, StdClass::class) === false
            ) {
                return new StdClass();
            }
            $this->object = $object;
        }
        return $this->object;
    }

    public function title(): string
    {
        $value = $this->valueForProperty('title');
        if ($value === false) {
            return '';
        }
        return $value;
    }

    public function __call(
        string $name,
        array $args = []
    ): string|int|float|bool|array|object {
        return $this->valueForProperty($name);
    }

    private function valueForProperty(
        string $name
    ): string|int|float|bool|array|object {
        if (property_exists($this->object(), $name)) {
            return $this->object()->{$name};
        }
        return false;
    }
}
