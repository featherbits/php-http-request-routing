<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting;


class RegexRoute extends Route
{
    function navigate(RoutePath $path, RequestMethod $method): RouteNavigationResult
    {
        return RegexRouteNavigationResult::create(
            preg_match($this->path->getValue(), $path->getValue(), $regexSearchMatches) === 1,
            $this->getRequestMethodHandlerFactory($method),
            $regexSearchMatches);
    }
}