<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting;

interface RegexSearchResultSetter
{
    function setRegexSearchMatches(array $matches): void;
}