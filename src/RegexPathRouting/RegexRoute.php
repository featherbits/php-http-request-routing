<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting\RegexPathRouting;

use Featherbits\HttpRequestRouting\Common\PrivateRoutePath;

use Featherbits\HttpRequestRouting\Contract\{
    Route, RouteRequestMethodHandler
};

use Featherbits\HttpRequestRouting\{
    RequestMethod, RoutePath, RouteNavigationResult
};

use Featherbits\HttpRequestRouting\RegexPathRouting\Contract\RegexRouteRequestMethodHandlerFactory;

class RegexRoute implements Route
{
    use PrivateRoutePath;

    private $handlerFactories = [];

    function __construct(RequestMethod $method, RoutePath $path, RegexRouteRequestMethodHandlerFactory $handlerFactory)
    {
        $this->path = $path;
        $this->setRequestMethodHandlerFactory($method, $handlerFactory);
    }

    function setRequestMethodHandlerFactory(RequestMethod $method, RegexRouteRequestMethodHandlerFactory $handlerFactory): void
    {
        $this->handlerFactories[$method->getValue()] = $handlerFactory;
    }

    function navigate(RequestMethod $method, RoutePath $path): RouteNavigationResult
    {
        $methodName = $method->getValue();
        return RouteNavigationResult::create($this->pregMatch($path, $regexSearchMatches),
            $this->handlerFactories[$methodName] ? $this->handlerFactories[$methodName]->create($regexSearchMatches) : null);
    }

    protected function pregMatch(RoutePath $path, &$regexSearchMatches): bool
    {
        return preg_match($this->path->getValue(), $path->getValue(), $regexSearchMatches) === 1;
    }
}