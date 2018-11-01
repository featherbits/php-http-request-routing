<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting\RegexPathRouting\Contract;

use Featherbits\HttpRequestRouting\Contract\RouteRequestMethodHandler;

interface RegexRouteRequestMethodHandlerFactory
{
    function create(array $regexSearchMatches): RouteRequestMethodHandler;
}