<?php

namespace Ztsu\Reacon;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Delegate adapter
 *
 * Adapts callable to DelegateInterface
 */
class DelegateAdapter implements DelegateInterface
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @param ServerRequestInterface $request
     * @return mixed
     */
    public function process(ServerRequestInterface $request)
    {
        return $this->callable->__invoke($request);
    }
}