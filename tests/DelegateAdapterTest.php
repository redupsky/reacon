<?php

namespace Ztsu\Reacon;

use Ztsu\Pipe\PipelineInterface;
use Psr\Http\Message\ServerRequestInterface;

class DelegateAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldInvokePipelineOnce()
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $pipeline = $this->createMock(PipelineInterface::class);
        $pipeline->expects($this->once())->method("__invoke")->with($request);

        $delegate = new DelegateAdapter($pipeline);

        $delegate->process($request);
    }
}