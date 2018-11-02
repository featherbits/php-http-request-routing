<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting;

class StaticRouteNavigationResult extends RouteNavigationResult
{
    static function create(bool $pathMatched, ?RequestMethodHandlerFactory $handlerFactory): self
    {
        return new self($pathMatched, $handlerFactory);
    }
}