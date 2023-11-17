<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Pengaturan\UbahPassword;

use Phalcon\Mvc\Controller as BaseController;
use App\Modules\Defaults\Master\Hakakses\Model as RoleModel;
use Core\Facades\Response;
use App\Modules\Defaults\Middleware\Controller as MiddlewareHardController;
use Core\Facades\Request;
use App\Modules\Defaults\Pengaturan\User\Model as Model;
use Core\Facades\Security;

/**
 * @routeGroup("/pengaturan/ubah-password")
 */
class Controller extends BaseController
{
    /**
     * @routeGet("/")
     */
    public function indexAction($id)
    {
        $this->view->setVar('module', $id);
    }



    /**
     * @routePost("/getPassword")
     */
    public function getPasswordAction()
    {
        $id = $this->session->user['id'];
        $user = Model::findFirst($id);

        $oldPassword = Request::getPost('oldPassword');
        // var_dump(Security::checkHash($oldPassword, $user->password));exit;
        if (Security::checkHash($oldPassword, $user->password)) {
            return Response::setJsonContent(true);
        } else {
            return Response::setJsonContent(false);
        }
    }

    /**
     * @routePost("/setPassword")
     */
    public function setPasswordAction()
    {
        $id = $this->session->user['id'];
        $user = Model::findFirst($id);
        $user->password = Security::hash(Request::getPost('password_new'), 10);

        $user->save();

        return Response::setJsonContent([
            'message' => 'Success',
        ]);
    }

    
}