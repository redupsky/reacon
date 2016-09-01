<?php

namespace Ztsu\Reacon\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Middleware\DelegateInterface;

class CreateResponseMiddleware implements ServerMiddlewareInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private $factory;
    
    /**
     * @param ResponseFactoryInterface $factory
     */
    public function __construct(ResponseFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, DelegateInterface $frame)
    {
        return $this->factory->createResponse();
    }
}