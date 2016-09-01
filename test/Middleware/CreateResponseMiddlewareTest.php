<?php

namespace test\Ztsu\Reacon\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Middleware\DelegateInterface;
use Ztsu\Reacon\Middleware\CreateResponseMiddleware;

class CreateResponseMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    public function testRunOnEmptyStack()
    {
        $original = $this->createMock(ResponseInterface::class);

        $factory = $this->createMock(ResponseFactoryInterface::class);

        $factory->expects($this->once())
            ->method("createResponse")
            ->willReturn($original);

        $middleware = new CreateResponseMiddleware($factory);

        $response = $middleware->process(
            $this->createMock(ServerRequestInterface::class),
            $this->createMock(DelegateInterface::class)
        );

        $this->assertSame($original, $response);
    }
}
