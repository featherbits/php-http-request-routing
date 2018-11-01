<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting\Contract;

use Featherbits\HttpRequestRouting\{
    RequestMethod, RoutePath, RouteNavigationResult
};

interface Route
{
    function navigate(RequestMethod $method, RoutePath $path): RouteNavigationResult;
}