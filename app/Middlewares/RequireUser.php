<?php

namespace App\Middlewares;

use Core\Facades\Session;
use Core\Facades\Dispatcher;
use Core\Route\AbstractMiddleware;

class RequireUser extends AbstractMiddleware
{
    public function beforeExecute()
    {
        if (!Session::has('user')) {
            // echo '<pre>';
            // print_r("Masuk Middleware");
            // echo '</pre>';
            // die();
            return Dispatcher::forward([
                'namespace'  => 'App\\Modules\\',
                'controller' => 'Errors',
                'action'     => 'unauthorized',
                'params'     => ['requireUser'],
            ]);
        }
    }
}
