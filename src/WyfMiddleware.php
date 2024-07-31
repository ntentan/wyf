<?php
namespace ntentan\wyf;

use ntentan\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class WyfMiddleware implements Middleware
{
    #[\Override]
    public function configure(array $configuration)
    {

    }

    #[\Override]
    public function run(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        var_dump($request->getUri());
        return $response;
    }
}
