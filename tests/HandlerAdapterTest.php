<?php

namespace Ztsu\Reacon;

use Psr\Http\Message\ResponseInterface;
use Ztsu\Pipe\PipelineInterface;
use Psr\Http\Message\ServerRequestInterface;

class HandlerAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldInvokePipelineOnce()
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $pipeline = $this->createMock(PipelineInterface::class);
        $pipeline->expects($this->once())
            ->method("__invoke")
            ->with($request)
            ->willReturn(
                $this->createMock(ResponseInterface::class)
            );

        $handler = new HandlerAdapter($pipeline);

        $handler->handle($request);
    }
}