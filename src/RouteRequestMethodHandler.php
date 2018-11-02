<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting;

interface RouteRequestMethodHandler
{
    /**
     * Executes request method handler
     */
    function execute(): void;
}