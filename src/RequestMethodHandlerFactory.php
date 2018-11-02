<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting;

interface RequestMethodHandlerFactory
{
    function create(): RouteRequestMethodHandler;
}