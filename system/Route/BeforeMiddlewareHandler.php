<?php

declare(strict_types=1);

namespace Core\Route;

class BeforeMiddlewareHandler extends MiddlewareHandler
{
    public function __invoke()
    {
        $middlewares = $this->getMiddlewares();
        foreach($middlewares as $middleware) {
            $middlewareObject = is_string($middleware)
                ? new $middleware
                : $middleware;

            $result = $middlewareObject->beforeExecute();
            if(isset($result)) {
                return $result;
            }
        }
    }
}