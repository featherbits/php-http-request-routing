<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Featherbits\HttpRequestRouting\RegexRoutePathFactory;

final class RegexRoutePathFactoryTest extends TestCase
{
    function testRegexPathParameterPatternYieldsExtractedValuesByName()
    {
        $factory = new RegexRoutePathFactory();
        $factory->setParameterTypeRegexPattern('testType', '[0-9]+\-[a-z\-a-z]+');

        $path = $factory->create('/some/path/{title:testType}');

        $this->assertTrue(preg_match($path->getValue(), '/some/path/1337-hello-world', $matches) === 1);
        $this->assertSame('1337-hello-world', $matches['title'] ?? null);
    }
}