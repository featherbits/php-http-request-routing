<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting;

use Featherbits\HttpRequestRouting\Contract\RouteRequestMethodHandler;

class RouteNavigationResult
{
    private $pathMatched;
    private $handler;

    protected function __construct(bool $pathMatched, ?RouteRequestMethodHandler $handler)
    {
        $this->pathMatched = $pathMatched;
        $this->handler = $handler;
    }

    final static function create(bool $pathMatched, ?RouteRequestMethodHandler $handler): self
    {
        return new self($pathMatched, $handler);
    }

    final function isPathMatched(): bool
    {
        return $this->pathMatched;
    }

    final function getHandler(): ?RouteRequestMethodHandler 
    {
        return $this->handler;
    }
}