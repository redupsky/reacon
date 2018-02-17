Reacon
======

The simplest PSR-15 complaint middleware runner on top of [ztsu/pipe](https://github.com/ztsu/pipe).

The [PSR-15](https://www.php-fig.org/psr/psr-15/) providers a standard
for recommendations that defines the interface for server middleware-component compatible with
[PSR-7 HTTP Messages](http://www.php-fig.org/psr/psr-7/).

There are a few of another PSR-15 compatible dispatchers. [Middleman](https://github.com/mindplay-dk/middleman) is the 
best known. Also there are a lot of PSR-15 middleware-components collected in the 
[middlewares/psr15-middlewares](https://github.com/middlewares/psr15-middlewares).

## Installation

Via [Composer](https://getcomposer.org/):

```bash
$ composer require ztsu/reacon
```

## Usage

```php
<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HelloMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $response = new Zend\Diactoros\Response();

        $response->getBody()->write("Hello, World!");

        return $response;
    }
}

$reacon = new Ztsu\Reacon\Reacon(
    [
        new HelloMiddleware(),
    ]
);

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals();

$response = $reacon->handle($request);

(new Zend\Diactoros\Response\SapiEmitter)->emit($response);

```

## License

MIT.