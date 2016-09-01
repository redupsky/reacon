<?php

namespace test\Ztsu\Reacon;

use Zend\Diactoros\Response;
use Psr\Http\Message\ResponseFactoryInterface;

class ZendDiactorosResponseFactory implements ResponseFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createResponse($status = 200)
    {
        $response = new Response();

        $response = $response->withStatus($status);

        return $response;
    }
}
