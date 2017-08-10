ztsu/reacon
===========

The simplest PSR-15 complaint middleware runner on top of [ztsu/pipe](https://github.com/ztsu/pipe).

The [PSR-15](https://github.com/php-fig/fig-standards/tree/master/proposed/http-middleware) providers a standard
for recommendations that defines the interface for server middleware-component compatible with
[PSR-7 HTTP Messages](http://www.php-fig.org/psr/psr-7/). The standard is just a draft. Reacon is using
[http-interop/http-middleware](https://github.com/http-interop/http-middleware) that contains last version
of PSR-15 interfaces.

There are a few of another PSR-15 compatible dispatchers.
[Middleman](https://github.com/mindplay-dk/middleman) is the best known.
 
Also there are a lot of PSR-15 middleware-components collected
in the [middlewares/psr15-middlewares](https://github.com/middlewares/psr15-middlewares).

## A simple example

```php
<?php

class HelloMiddleware implements Interop\Http\ServerMiddleware\MiddlewareInterface
{
    public function process(
        Psr\Http\Message\ServerRequestInterface $request,
        Interop\Http\ServerMiddleware\DelegateInterface $delegate
    ) {
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

$response = $reacon->run($request);

(new Zend\Diactoros\Response\SapiEmitter)->emit($response);

```

## Installation

Via [Composer](https://getcomposer.org/):

```bash
$ composer require "ztsu/reacon"
```

## License

MIT.