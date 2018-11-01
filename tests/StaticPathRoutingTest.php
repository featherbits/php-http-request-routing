<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Featherbits\HttpRequestRouting\Contract\RouteRequestMethodHandler;
use Featherbits\HttpRequestRouting\{
    RequestMethod, RoutePath, RouteNavigationResult
};

use Featherbits\HttpRequestRouting\StaticPathRouting\StaticRoute;
use Featherbits\HttpRequestRouting\StaticPathRouting\Contract\StaticRouteRequestMethodHandlerFactory;

final class StaticPathRoutingTest extends TestCase
{
    function testRouteNavigates()
    {
        $method = RequestMethod::create(RequestMethod::GET);
        $path = RoutePath::create('/some/path');
        $factory = new class implements StaticRouteRequestMethodHandlerFactory
        {
            public $handler;

            function create(): RouteRequestMethodHandler
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

        $route = new StaticRoute($method, $path, $factory);
        $result = $route->navigate($method, $path);

        $this->assertTrue($result->isPathMatched(), 'Route path did not match');
        $this->assertNotNull($factory->handler, 'Handler factory was not called');
        $this->assertNotNull($result->getHandler(), 'Handler was not obtained');
        $this->assertSame($factory->handler, $result->getHandler(),
            'Handler instance created by factory is different from handler instance returned by navigation result');

        $result->getHandler()->execute();

        $this->assertTrue($factory->handler->executed, 'Handler was not executed');
    }
}