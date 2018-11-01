<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting\Contract;

interface RouteRequestMethodHandler
{
    /**
     * Executes request method handler
     */
    function execute(): void;
}