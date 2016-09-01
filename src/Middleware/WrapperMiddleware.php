<?php

namespace Ztsu\Reacon\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Middleware\DelegateInterface;

class WrapperMiddleware implements ServerMiddlewareInterface
{
    /**
     * @var callable
     */
    private $middleware;
    
    /**
     * @param callable $middleware
     */
    public function __construct(callable $middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, DelegateInterface $frame)
    {
        return $this->middleware->__invoke($request, $frame);
    }
}