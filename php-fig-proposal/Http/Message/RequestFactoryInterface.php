<?php
/**
 * Copyright (c) 2013-2016 PHP Framework Interop Group
 *
 * Licensed under MIT License (@link https://github.com/php-fig/fig-standards/blob/master/LICENSE-MIT.md)
 *
 * This code is a part of PSR-17 proposal (HTTP Factories). For more information
 * see @link https://github.com/php-fig/fig-standards/tree/master/proposed/http-factory
 */

namespace Psr\Http\Message;

use Psr\Http\Message\RequestInterface;

interface RequestFactoryInterface
{
    /**
     * Create a new request.
     *
     * @param string $method
     * @param UriInterface|string $uri
     *
     * @return RequestInterface
     */
    public function createRequest($method, $uri);
}