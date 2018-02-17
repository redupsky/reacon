<?php

namespace Ztsu\Reacon\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ztsu\Reacon\Reacon;

class CallableMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $middleware = $this->createPartialMock(\stdClass::class, ["__invoke"]);

        $middleware
            ->expects($this->once())
            ->method("__invoke")
            ->withConsecutive(
                $request,
                $this->isInstanceOf(RequestHandlerInterface::class)
            )
            ->willReturn(
                $this->createMock(ResponseInterface::class)
            );

        $reacon = new Reacon([
            new CallableMiddleware($middleware)
        ]);

        $response = $reacon->handle($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}