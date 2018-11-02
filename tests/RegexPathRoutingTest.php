<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Featherbits\HttpRequestRouting\{
    RoutePath,
    RegexRoute,
    RequestMethod,
    RouteRequestMethodHandler,
    RequestMethodHandlerFactory
};

class RegexPathRoutingTest extends TestCase
{
    function testRouteNavigates()
    {
        $method = RequestMethod::create(RequestMethod::GET);
        $regexPath = RoutePath::create('/^\/some\/path$/');
        $requestPath = RoutePath::create('/some/path');
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

        $route = new RegexRoute($regexPath, $method, $factory);
        $result = $route->navigate($requestPath, $method);

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