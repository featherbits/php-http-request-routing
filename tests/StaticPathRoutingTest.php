<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Featherbits\HttpRequestRouting\{
    RoutePath,
    StaticRoute,
    RequestMethod,
    RouteRequestMethodHandler,
    RequestMethodHandlerFactory
};

final class StaticPathRoutingTest extends TestCase
{
    function testRouteNavigates()
    {
        $method = RequestMethod::create(RequestMethod::GET);
        $path = RoutePath::create('/some/path');
        $factory = new class implements RequestMethodHandlerFactory
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

        $route = new StaticRoute($path, $method, $factory);
        $result = $route->navigate($path, $method);

        $this->assertTrue($result->isPathMatched(), 'Route path did not match');

        $obtainedHandler = $result->getHandler();

        $this->assertNotNull($obtainedHandler, 'Handler was not obtained');
        $this->assertNotNull($factory->handler, 'Handler factory was not called');
        
        $this->assertSame($factory->handler, $obtainedHandler,
            'Handler instance created by factory is different from handler instance returned by navigation result');

        $obtainedHandler->execute();

        $this->assertTrue($factory->handler->executed, 'Handler was not executed');
    }
}