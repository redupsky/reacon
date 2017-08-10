<?php

namespace Ztsu\Reacon;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Ztsu\Pipe\Pipeline;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;

/**
 * Reacon
 *
 * Runs pipeline of middlewares
 */
class Reacon implements MiddlewareInterface
{
    /**
     * @var \Ztsu\Pipe\Pipeline
     */
    private $pipeline;

    /**
     * @param []MiddlewareInterface $middleware
     */
    public function __construct(array $middleware = [])
    {
        $this->pipeline = new Pipeline();

        foreach ($middleware as $middleware) {
            $this->add($middleware);
        }
    }

    /**
     * @param MiddlewareInterface $middleware
     * @return $this
     */
    public function add(MiddlewareInterface $middleware)
    {
        $this->pipeline->add(
            function(ServerRequestInterface $request, callable $next) use ($middleware) {
                return $middleware->process($request, new DelegateAdapter($next));
            }
        );

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $request)
    {
        return $this->pipeline->run($request);
    }

    /*
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $result = $this->run($request);

        if ($result instanceof ResponseInterface) {
            return $result;

        }
        return $delegate->process($result);
    }
}


