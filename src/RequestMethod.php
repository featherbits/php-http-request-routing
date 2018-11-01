<?php declare(strict_types=1);

namespace Featherbits\HttpRequestRouting;

use RuntimeException;

class RequestMethod
{   
    const GET = 'GET';
    const PUT = 'PUT';
    const POST = 'POST';
    const HEAD = 'HEAD';
    const TRACE = 'TRACE';
    const PATCH = 'PATCH';
    const DELETE = 'DELETE';
    const CONNECT = 'CONNECT';
    const OPTIONS = 'OPTIONS';

    private $requestMethod;

    private function __construct(string $requestMethod)
    {
        if (empty($requestMethod))
        {
            throw new RuntimeException('Request method cannot be empty');
        }

        if ($requestMethod === self::GET
            or $requestMethod === self::POST
            or $requestMethod === self::PUT
            or $requestMethod === self::OPTIONS
            or $requestMethod === self::DELETE
            or $requestMethod === self::PATCH
            or $requestMethod === self::HEAD
            or $requestMethod === self::TRACE
            or $requestMethod === self::CONNECT)
        {
            $this->requestMethod = $requestMethod;
        }
        else
        {
            throw new RuntimeException($requestMethod . ' is not a valid value for request method');
        }
    }

    static function create(string $requestMethod): self
    {
        return new self($requestMethod);
    }

    function getValue(): string
    {
        return $this->requestMethod;
    }
}