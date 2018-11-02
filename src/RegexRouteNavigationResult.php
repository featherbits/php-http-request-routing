<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting;

class RegexRouteNavigationResult extends RouteNavigationResult
{
    /**
     * @var array
     */
    private $regexSearchMatches;

    static function create(bool $pathMatched, ?RequestMethodHandlerFactory $handlerFactory, array $regexSearchMatches): self
    {
        $instance = new self($pathMatched, $handlerFactory);
        $instance->regexSearchMatches = $regexSearchMatches;
        
        return $instance;
    }

    function getHandler(): ?RouteRequestMethodHandler 
    {
        $handler = parent::getHandler();

        if ($handler instanceof RegexSearchResultSetter)
        {
            $handler->setRegexSearchMatches($this->regexSearchMatches);
        }

        return $handler;
    }
}