<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Featherbits\HttpRequestRouting\Contract\RouteRequestMethodHandler;
use Featherbits\HttpRequestRouting\{
    RequestMethod, RoutePath, RouteNavigationResult
};

use Featherbits\HttpRequestRouting\RegexPathRouting\RegexRoute;
use Featherbits\HttpRequestRouting\RegexPathRouting\Contract\RegexRouteRequestMethodHandlerFactory;

class RegexPathRoutingTest extends TestCase
{
    function testRouteNavigates()
    {
        $method = RequestMethod::create(RequestMethod::GET);
        $regexPath = RoutePath::create('/^\/some\/path$/');
        $requestPath = RoutePath::create('/some/path');
        $factory = new class implements RegexRouteRequestMethodHandlerFactory
        {
            public $handler;

            function create(array $regexSearchMatches): RouteRequestMethodHandler
            {
                return $this->handler = new class implements RouteRequestMethodHandler
                {
                    public $executed = false;

                    function execute(): void
                    {
                        $this->executed = true;
                    }
                };
            }
        };

        $route = new RegexRoute($method, $regexPath, $factory);
        $result = $route->navigate($method, $requestPath);

        $this->assertTrue($result->isPathMatched(), 'Route path did not match');
        $this->assertNotNull($factory->handler, 'Handler factory was not called');
        $this->assertNotNull($result->getHandler(), 'Handler was not obtained');
        $this->assertSame($factory->handler, $result->getHandler(),
            'Handler instance created by factory is different from handler instance returned by navigation result');

        $result->getHandler()->execute();

        $this->assertTrue($factory->handler->executed, 'Handler was not executed');
    } 
}