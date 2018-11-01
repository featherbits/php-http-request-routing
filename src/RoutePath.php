<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting;

use RuntimeException;

class RoutePath
{
    private $path;

    private function __construct(string $path)
    {
        if (empty($path))
        {
            throw new RuntimeException('Route path cannot be empty');
        }

        $this->path = $path;
    }

    static function create(string $path): self
    {
        return new self($path);
    }

    function getValue(): string
    {
        return $this->path;
    }
}