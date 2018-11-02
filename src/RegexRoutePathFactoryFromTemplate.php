<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting;

use RuntimeException;

class RegexRoutePathFactoryFromTemplate
{
    protected $parameterTypeRegexPatterns = [
        '' => '[\w\-]+'
    ];

    function create(string $routePathTemplate, ?array $scopedParameterTypeRegexPatterns): RoutePath
    {
        $path = rtrim($routePathTemplate, '/');
        $path = str_replace('/', '\/', $path);
        
        // search for parameter templates in path
        $matchCount = preg_match_all('/\{([a-zA-z_]+[a-zA-z_0-9]+:[a-zA-z_]+[a-zA-z_0-9]+)\}/', $path, $matches);
        $paramNames = [];

        if ($matchCount > 0)
        {
            foreach ($matches[1] as $templateIndex => $parameterInfo)
            {
                $segments = explode(':', $parameterInfo);

                $parameterName = $segments[0];
                $parameterTypeName = $segments[1] ?? '';
                
                $typePattern = $this->getTypeRegexPattern($parameterTypeName, $scopedParameterTypeRegexPatterns);

                // replace parameter template with regex pattern
                $path = str_replace($matches[0][$templateIndex], "(?<$parameterName>$typePattern)", $path);

                $paramNames[] = $parameterName;
            }
        }

        // form final regex pattern for route path with optional trailing slash
        $path = "/^$path\/*$/";

        return new RoutePath($path);
    }

    protected function getTypeRegexPattern(string $parameterTypeName, ?array $scopedParameterTypeRegexPatterns): string
    {
        if ($scopedParameterTypeRegexPatterns !== null and isset($scopedParameterTypeRegexPatterns[$parameterTypeName]))
        {
            return $scopedParameterTypeRegexPatterns[$parameterTypeName];
        }
        else if (isset($this->parameterTypeRegexPatterns[$parameterTypeName]))
        {
            return $this->parameterTypeRegexPatterns[$parameterTypeName];
        }

        throw new RuntimeException("Regex pattern for parameter type [$parameterTypeName] not set");
    }

    function setParameterTypeRegexPattern(string $parameterTypeName, string $regexPattern)
    {
        if (empty($parameterTypeName))
        {
            throw new RuntimeException('Parameter type name cannot be empty');
        }

        if (empty($regexPattern))
        {
            throw new RuntimeException('Parameter regex patter cannot be empty');
        }

        $this->parameterTypeRegexPatterns[$parameterTypeName] = $regexPattern;
    }
}
