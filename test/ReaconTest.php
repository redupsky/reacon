<?php

namespace test\Ztsu\Reacon;

use Zend\Diactoros\ServerRequestFactory;
use Ztsu\Reacon\Middleware\CreateResponseMiddleware;
use Ztsu\Reacon\Reacon;
use Ztsu\Reacon\Exception;

class ReaconTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $reacon = new Reacon(
            [
                new CreateResponseMiddleware(new ZendDiactorosResponseFactory()),
            ]
        );

        $response = $reacon->process(ServerRequestFactory::fromGlobals());

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame("", $response->getBody()->__toString());
        $this->assertEmpty($response->getHeaders());
    }

    public function testOnEmptyStack()
    {
        $this->expectException(Exception::class);

        (new Reacon)->process(ServerRequestFactory::fromGlobals());
    }
}
