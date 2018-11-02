<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting;

class StaticRoute extends Route
{
    function navigate(RoutePath $path, RequestMethod $method): RouteNavigationResult
    {
        return StaticRouteNavigationResult::create($path->getValue() === $this->path->getValue(),
            $this->getRequestMethodHandlerFactory($method));
    }
}