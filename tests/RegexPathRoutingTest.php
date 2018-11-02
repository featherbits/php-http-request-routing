<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Featherbits\HttpRequestRouting\{
    RoutePath,
    RegexRoute,
    RequestMethod,
    RegexSearchResultSetter,
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

    function testRegexSearchResultsAreSet()
    {
        $method = RequestMethod::create(RequestMethod::GET);
        $regexPath = RoutePath::create('/^\/some\/path$/');
        $requestPath = RoutePath::create('/some/path');
        $factory = new class implements RequestMethodHandlerFactory
        {
            function create(): RouteRequestMethodHandler
            {
                return new class implements RouteRequestMethodHandler, RegexSearchResultSetter
                {
                    public $matches;

                    function setRegexSearchMatches(array $matches): void
                    {
                        $this->matches = $matches;
                    }

                    function execute(): void {}
                };
            }
        };

        $route = new RegexRoute($regexPath, $method, $factory);
        $matches = $route->navigate($requestPath, $method)->getHandler()->matches;

        $this->assertTrue(is_array($matches), 'Regex search result was not set');
        $this->assertSame('/some/path', $matches[0] ?? null, 'Regex search result did not contain matched path');
    }
}