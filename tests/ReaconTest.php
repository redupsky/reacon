<?php

namespace Ztsu\Reacon;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;

class ReaconTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $middleware1 = $this->createMock(MiddlewareInterface::class);
        $middleware1->expects($this->once())
            ->method("process")
            ->willReturnCallback(
                function(
                    \Psr\Http\Message\ServerRequestInterface $request,
                    \Interop\Http\ServerMiddleware\DelegateInterface $delegate
                ) {
                    return $delegate->process($request);
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

        $response = $reacon->run($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}