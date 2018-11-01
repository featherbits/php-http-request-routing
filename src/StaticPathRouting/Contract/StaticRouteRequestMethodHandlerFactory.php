<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting\StaticPathRouting\Contract;

use Featherbits\HttpRequestRouting\Contract\RouteRequestMethodHandler;

interface StaticRouteRequestMethodHandlerFactory
{
    function create(): RouteRequestMethodHandler;
}