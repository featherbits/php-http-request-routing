<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting;

abstract class RouteNavigationResult
{
    /**
     * @var bool
     */
    private $pathMatched;

    /**
     * @var RequestMethodHandlerFactory|null
     */
    private $handlerFactory;

    protected function __construct(bool $pathMatched, ?RequestMethodHandlerFactory $handlerFactory)
    {
        $this->pathMatched = $pathMatched;
        $this->handlerFactory = $handlerFactory;
    }

    function isPathMatched(): bool
    {
        return $this->pathMatched;
    }

    function getHandler(): ?RouteRequestMethodHandler 
    {
        return ($this->pathMatched and $this->handlerFactory !== null)
            ? $this->handlerFactory->create() : null;
    }
}