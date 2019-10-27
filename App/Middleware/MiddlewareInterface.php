<?php

declare(strict_types=1);

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Interface MiddlewareInterface
 */
interface MiddlewareInterface
{
    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next): Response;
}
