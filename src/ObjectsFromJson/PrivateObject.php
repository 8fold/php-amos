<?php
declare(strict_types=1);

namespace Eightfold\Amos\ObjectsFromJson;

use StdClass;
use DateTime;

use Eightfold\Amos\Php\Interfaces\Findable;

use Eightfold\Amos\FileSystem\Path;

use Eightfold\Amos\FileSystem\Directories\Root;
use Eightfold\Amos\PlainText\PrivateJson as PlainTextPrivateJson;

final class PrivateObject implements Findable
{
    private StdClass $object;

    public static function inRoot(
        Root $root,
        string $filename,
        string|Path $at = ''
    ): self {
        if (is_string($at)) {
            $at = Path::createFromString($at);
        }
        return new self(
            PlainTextPrivateJson::inRoot($root, $filename, $at)
        );
    }

    private function __construct(
        private readonly PlainTextPrivateJson $publicJson
    ) {
    }

    public function notFound(): bool
    {
        return ! $this->toBool();
    }

    public function found(): bool
    {
        return $this->toBool();
    }

    public function exists(): bool
    {
        return $this->toBool();
    }

    public function nonexistent(): bool
    {
        return ! $this->toBool();
    }

    public function toBool(): bool
    {
        return $this->publicJson->toBool();
    }

    public function hasProperty(string $property): bool
    {
        $object = $this->object();
        return property_exists($object, $property);
    }

    public function object(): StdClass
    {
        if (isset($this->object) === false) {
            $json = $this->publicJson->toString();

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

    /**
     * @param array<mixed> $args
     *
     * @return string|int|float|bool|array<mixed>|object
     */
    public function __call(
        string $name,
        array $args = []
    ): string|int|float|bool|array|object {
        return $this->valueForProperty($name);
    }

    /**
     * @return string|int|float|bool|array<mixed>|object
     */
    private function valueForProperty(
        string $name
    ): string|int|float|bool|array|object {
        if (property_exists($this->object(), $name)) {
            return $this->object()->{$name};
        }
        return false;
    }
}
