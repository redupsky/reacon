<?php

namespace Ztsu\Reacon;

use InvalidArgumentException;
use LogicException;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ReaconTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration;

    public function testShouldBeNotInitiatedWithoutMiddleare()
    {
        $this->expectException(InvalidArgumentException::class);

        new Reacon();
    }

    public function testShouldBeServerRequestHandlerInstance()
    {
        $reacon = new Reacon(\Mockery::mock(MiddlewareInterface::class));

        $this->assertInstanceOf(RequestHandlerInterface::class, $reacon);
    }

    public function testShouldInvokeAllMiddlewareInTheRightOrder()
    {

        $middleware1 = \Mockery::mock(MiddlewareInterface::class)
            ->shouldReceive("process")
            ->once()
            ->andReturnUsing(
                function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
                return $handler->handle($request);
                }
            )
            ->globally()->ordered()
            ->getMock();

        $middleware2 = \Mockery::mock(MiddlewareInterface::class)
            ->shouldReceive("process")
            ->once()
            ->andReturnUsing(
                function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
                    return $handler->handle($request);
                }
            )
            ->globally()->ordered()
            ->getMock();

        $middleware3 = \Mockery::mock(MiddlewareInterface::class)
            ->shouldReceive("process")
            ->once()
            ->andReturnUsing(
                function () {
                    return \Mockery::mock(ResponseInterface::class);
                }
            )
            ->globally()->ordered()
            ->getMock();

        $reacon = new Reacon($middleware1, $middleware2, $middleware3);

        $reacon->handle(\Mockery::mock(ServerRequestInterface::class));
    }

    public function testShouldBeUsedMoreThanOnce()
    {
        $middleware1 = \Mockery::mock(MiddlewareInterface::class)
            ->shouldReceive("process")
            ->twice()
            ->andReturnUsing(
                function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
                    return $handler->handle($request);
                }
            )
            ->getMock();

        $middleware2 = \Mockery::mock(MiddlewareInterface::class)
            ->shouldReceive("process")
            ->twice()
            ->andReturnUsing(
                function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
                    return $handler->handle($request);
                }
            )
            ->getMock();

        $middleware3 = \Mockery::mock(MiddlewareInterface::class)
            ->shouldReceive("process")
            ->twice()
            ->andReturnUsing(
                function () {
                    return \Mockery::mock(ResponseInterface::class);
                }
            )
            ->getMock();

        $reacon = new Reacon($middleware1, $middleware2, $middleware3);

        $reacon->handle(\Mockery::mock(ServerRequestInterface::class));
        $reacon->handle(\Mockery::mock(ServerRequestInterface::class));
    }

    public function testShouldNotPassBeyondMiddlewareThatCreatesResponse()
    {
        $middleware1 = \Mockery::mock(MiddlewareInterface::class)
            ->shouldReceive("process")
            ->once()
            ->andReturnUsing(
                function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
                    return $handler->handle($request);
                }
            )
            ->getMock();

        $middleware2 = \Mockery::mock(MiddlewareInterface::class)
            ->shouldReceive("process")
            ->once()
            ->andReturnUsing(
                function () {
                    return \Mockery::mock(ResponseInterface::class);
                }
            )
            ->getMock();

        $middleware3 = \Mockery::mock(MiddlewareInterface::class)
            ->shouldReceive("process")
            ->never()
            ->getMock();

        $reacon = new Reacon($middleware1, $middleware2, $middleware3);

        $reacon->handle(\Mockery::mock(ServerRequestInterface::class));
    }

    public function testShouldRaiseExceptionIfThereIsNotMiddlewareThatCreatesResponseInThePipeline()
    {
        $this->expectException(LogicException::class);

        $middleware1 = new class implements MiddlewareInterface {
            public function process(
                ServerRequestInterface $request,
                RequestHandlerInterface $handler
            ): ResponseInterface {
                return $handler->handle($request);
            }
        };

        $reacon = new Reacon($middleware1);

        $reacon->handle(\Mockery::mock(ServerRequestInterface::class));
    }

    public function testShouldBeMiddlewareInstance()
    {
        $reacon = new Reacon(\Mockery::mock(MiddlewareInterface::class));

        $this->assertInstanceOf(MiddlewareInterface::class, $reacon);
    }

    public function testShouldBeUsedAsMiddleware()
    {
        $middleware1 = \Mockery::mock(MiddlewareInterface::class)
            ->shouldReceive("process")
            ->once()
            ->andReturnUsing(
                function (ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
                {
                    return $handler->handle($request);
                }
            )
            ->getMock();

        $reacon1 = new Reacon($middleware1);

        $middleware2 = \Mockery::mock(MiddlewareInterface::class)
            ->shouldReceive("process")
            ->once()
            ->andReturnUsing(
                function () {
                    return \Mockery::mock(ResponseInterface::class);
                }
            )
            ->getMock();

        $reacon2 = new Reacon(
            $reacon1,
            $middleware2
        );

        $reacon2->handle(\Mockery::mock(ServerRequestInterface::class));
    }
}