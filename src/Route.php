<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting;

abstract class Route
{
    protected $path;
    protected $requestMethodHandlerFactories = [];

    function __construct(RoutePath $path, RequestMethod $method, RequestMethodHandlerFactory $factory)
    {
        $this->path = $path;
        $this->setRequestMethodHandlerFactory($method, $factory);
    }

    function setRequestMethodHandlerFactory(RequestMethod $method, RequestMethodHandlerFactory $factory): void
    {
        $this->requestMethodHandlerFactories[$method->getValue()] = $factory;
    }

    protected function getRequestMethodHandlerFactory(RequestMethod $method): ?RequestMethodHandlerFactory
    {
        $methodName = $method->getValue();

        return $this->requestMethodHandlerFactories[$methodName]
            ? $this->requestMethodHandlerFactories[$methodName]: null;
    }

    abstract function navigate(RoutePath $path, RequestMethod $method): RouteNavigationResult;
}