<?php

namespace Ztsu\Reacon;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ReaconTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldBeServerRequestHandler()
    {
        $reacon = new Reacon();

        $this->assertInstanceOf(RequestHandlerInterface::class, $reacon);
    }

    public function test()
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $middleware1 = $this->createMock(MiddlewareInterface::class);
        $middleware1->expects($this->once())
            ->method("process")
            ->willReturnCallback(
                function(
                    \Psr\Http\Message\ServerRequestInterface $request,
                    RequestHandlerInterface $handler
                ) {
                    return $handler->handle($request);
                }
            );

        $middleware2 = $this->createMock(MiddlewareInterface::class);
        $middleware2->expects($this->once())
            ->method("process")
            ->with($request)
            ->willReturnCallback(
                function() {
                    return $this->createMock(ResponseInterface::class);
                }
            );

        $reacon = new Reacon();
        $reacon->add($middleware1);
        $reacon->add($middleware2);

        $response = $reacon->handle($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testShouldBeMiddleware()
    {
        $reacon = new Reacon();

        $this->assertInstanceOf(MiddlewareInterface::class, $reacon);
    }

    public function testShouldBeUsedAsMiddleware()
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $middleware1 = $this->createMock(MiddlewareInterface::class);
        $middleware1->expects($this->once())
            ->method("process")
            ->willReturnCallback(
                function(
                    \Psr\Http\Message\ServerRequestInterface $request,
                    RequestHandlerInterface $handler
                ) {
                    return $handler->handle($request);
                }
            );

        $middleware2 = $this->createMock(MiddlewareInterface::class);
        $middleware2->expects($this->once())
            ->method("process")
            ->with($request)
            ->willReturnCallback(
                function(
                    \Psr\Http\Message\ServerRequestInterface $request,
                    RequestHandlerInterface $handler
                ) {
                    return $this->createMock(ResponseInterface::class);
                }
            );

        $reacon1 = new Reacon(
            [
                $middleware1,
                $middleware2,
            ]
        );

        $middleware3 = $this->createMock(MiddlewareInterface::class);
        $middleware3->expects($this->once())
            ->method("process")
            ->with($request)
            ->willReturnCallback(
                function(
                    \Psr\Http\Message\ServerRequestInterface $request,
                    RequestHandlerInterface $handler
                ) {
                    return $handler->handle($request);
                }
            );

        $reacon2 = new Reacon(
            [
                $middleware3,
                $reacon1,
            ]
        );

        $response = $reacon2->handle($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}