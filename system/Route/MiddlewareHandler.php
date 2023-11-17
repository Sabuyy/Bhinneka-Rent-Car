<?php
namespace Core\Route;

use Phalcon\Mvc\Dispatcher;
use Phalcon\Registry;
use Core\Base\AbstractMiddeware;

abstract class MiddlewareHandler
{
    /**
     * @return AbstractMiddeware[]
     */
    public function getMiddlewares()
    {
        $middlewares = [];

        /** @var Registry $registry */
        $registry = container('registry');

        /** @var Dispatcher $dispatcher */
        $dispatcher = container('dispatcher');

        if($registry->has('globalMiddlewares')) {
            $middlewares = $registry->get('globalMiddlewares');
        }

        if($registry->has('actionMiddlewares')) {
            $actionMiddlewares = $registry->get('actionMiddlewares');
            $action = $dispatcher->getControllerClass() . ':' . $dispatcher->getActionName();

            if(array_key_exists($action, $actionMiddlewares))
                $middlewares = array_merge($middlewares, $actionMiddlewares[$action]);
        }

        return $middlewares;
    }

    public static function resolveMiddleware($middlewareName)
    {
        if(!class_exists($resolved = $middlewareName)
            && !class_exists($resolved = 'App\\Middleware\\' . $middlewareName)
            && !class_exists($resolved = 'App\\Middlewares\\' . $middlewareName))
            return false;

        if(!is_subclass_of($resolved, AbstractMiddleware::class))
            return false;

        return $resolved;
    }
}