<?php

namespace Ztsu\Reacon;

use InvalidArgumentException;
use LogicException;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
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
     * @var \SplObjectStorage
     */
    private $stages;

    /**
     * @var bool
     */
    private $rewinded;

    /**
     * @param MiddlewareInterface[] ...$middlewares
     */
    public function __construct(MiddlewareInterface ...$middlewares)
    {
        $this->stages = new \SplObjectStorage;

        if (empty($middlewares)) {
            throw new InvalidArgumentException("Middleware pipeline is empty");
        }

        foreach ($middlewares as $middleware) {
            $this->stages->attach($middleware);
        }

        $this->rewinded = false;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!$this->rewinded) {
            $this->rewinded = true;
            $this->stages->rewind();
        }

        return $this->invokeStage($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($handler instanceof MiddlewareInterface) {
            $this->stages->attach($handler);
        }

        return $this->handle($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    private function invokeStage(ServerRequestInterface $request)
    {
        /**
         * @var MiddlewareInterface $stage
         */
        $stage = $this->stages->current();

        if (!$stage instanceof MiddlewareInterface) {
            throw new LogicException("There is no more middleware in the pipeline");
        }

        $this->stages->next();

        $result = $stage->process($request, clone $this);

        $this->rewinded = false;

        return $result;
    }
}


