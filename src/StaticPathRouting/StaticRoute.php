<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting\StaticPathRouting;

use Featherbits\HttpRequestRouting\StaticPathRouting\Contract\StaticRouteRequestMethodHandlerFactory;
use Featherbits\HttpRequestRouting\Common\PrivateRoutePath;

use Featherbits\HttpRequestRouting\Contract\{
    Route, RouteRequestMethodHandler
};

use Featherbits\HttpRequestRouting\{
    RequestMethod, RoutePath, RouteNavigationResult
};

class StaticRoute implements Route
{
    use PrivateRoutePath;

    private $handlerFactories = [];

    function __construct(RequestMethod $method, RoutePath $path, StaticRouteRequestMethodHandlerFactory $factory)
    {
        $this->path = $path;
        $this->setRequestMethodHandlerFactory($method, $factory);
    }

    function setRequestMethodHandlerFactory(RequestMethod $method, StaticRouteRequestMethodHandlerFactory $factory): void
    {
        $this->handlerFactories[$method->getValue()] = $factory;
    }

    function navigate(RequestMethod $method, RoutePath $path): RouteNavigationResult
    {
        $methodName = $method->getValue();
        return RouteNavigationResult::create($path->getValue() === $this->path->getValue(),
            $this->handlerFactories[$methodName] ? $this->handlerFactories[$methodName]->create() : null);
    }
}