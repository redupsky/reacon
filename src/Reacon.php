<?php

namespace Ztsu\Reacon;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ztsu\Pipe\Pipeline;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Reacon
 *
 * Runs pipeline of middlewares
 */
class Reacon implements RequestHandlerInterface, MiddlewareInterface
{
    /**
     * @var \Ztsu\Pipe\Pipeline
     */
    private $pipeline;

    /**
     * @param []MiddlewareInterface $middlewares
     */
    public function __construct(array $middlewares = [])
    {
        $this->pipeline = new Pipeline();

        foreach ($middlewares as $middleware) {
            $this->add($middleware);
        }
    }

    /**
     * @param MiddlewareInterface $middleware
     * @return Reacon
     */
    public function add(MiddlewareInterface $middleware): Reacon
    {
        $this->pipeline->add(
            function(ServerRequestInterface $request, callable $next) use ($middleware) {
                return $middleware->process($request, new HandlerAdapter($next));
            }
        );

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->pipeline->run($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $result = $this->handle($request);

        if ($result instanceof ResponseInterface) {
            return $result;
        }

        return $handler->handle($result);
    }
}


