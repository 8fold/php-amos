<?php

declare(strict_types=1);

namespace Eightfold\Amos;

use Eightfold\Amos\File;

class Store
{
    /**
     * @var string[]
     */
    private array $rootParts = [];

    /**
     * @var string[]
     */
    private array $pathParts = [];

    public static function create(string $root): static
    {
        return new static($root);
    }

    final public function __construct(private string $root)
    {
        $this->rootParts = explode('/', $root);
    }

    /**
     * @return string[]
     */
    private function getRootParts(): array
    {
        return $this->rootParts;
    }

    /**
     * @return string[]
     */
    private function getPathParts(): array
    {
        return $this->pathParts;
    }

    public function getAbsolutePath(): string
    {
        $m = array_merge($this->getRootParts(), $this->getPathParts());
        $f = array_filter($m);
        return '/' . implode('/', $f);
    }

    public function isRoot(): bool
    {
        return count(array_filter($this->getPathParts())) === 0;
    }

    public function up(int $levels = 1): static
    {
        if (count($this->getPathParts()) === 0) {
            $parts = $this->getRootParts();

            for ($i = 0; $i < $levels; $i++) {
                array_pop($parts);
            }

            $this->rootParts = $parts;

        } else {
            $parts = $this->getPathParts();
            array_pop($parts);

            $this->pathParts = $parts;
        }
        return $this;
    }

    public function appendPath(string ...$folderNames): static
    {
        $this->pathParts = array_merge($this->getPathParts(), $folderNames);
        return $this;
    }

    public function hasFile(string $fileName): bool
    {
        $filePath = $this->filePath($fileName);
        return file_exists($filePath) and is_file($filePath);
    }

    public function getFile(string $fileName): File|bool
    {
        $file = File::create($this->filePath($fileName));
        if (is_object($file) and $file->isFile()) {
            return $file;
        }
        return false;
    }

    private function filePath(string $fileName): string
    {
        return $this->getAbsolutePath() . '/' . $fileName;
    }
}
