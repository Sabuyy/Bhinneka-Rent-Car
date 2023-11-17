<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Index;

use Phalcon\Mvc\Controller AS BaseController;
use Core\Facades\Response;
use Core\Facades\Session;

class Controller extends BaseController
{
    /**
     * @routeGet("/")
     */
    public function indexAction()
    {
        return Session::has('user')
            ? Response::redirect('/panel/dashboard')
            : Response::redirect('/auth/login')
        ;
    }
}