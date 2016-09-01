<?php

namespace Ztsu\Reacon;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Middleware\DelegateInterface;
use Psr\Http\Middleware\MiddlewareInterface;
use Psr\Http\Middleware\StackInterface;

class Reacon implements StackInterface, DelegateInterface
{
    /**
     * @var \SplObjectStorage
     */
    private $stages;

    /**
     * @param MiddlewareInterface[] $middlewares
     */
    public function __construct(array $middlewares = [])
    {
        $this->stages = new \SplObjectStorage;

        foreach ($middlewares as $middleware) {
            $this->withMiddleware($middleware);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function withMiddleware(MiddlewareInterface $middleware)
    {
        $this->stages->attach($middleware);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withoutMiddleware(MiddlewareInterface $middleware)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function process(RequestInterface $request)
    {
        $this->stages->rewind();

        $middleware = $this->stages->current();

        if (is_null($middleware)) {
            throw new Exception("The stack is empty");
        }

        return $middleware->process($request, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function next(RequestInterface $request)
    {
        $this->stages->next();

        $middleware = $this->stages->current();

        if (is_null($middleware)) {
            throw new Exception("There are no middlewares in the stack");
        }

        return $middleware->process($request, $this);
    }
}