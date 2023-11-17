<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Auth\Controller;

use App\Modules\Defaults\Auth\Model\PdamModel;
use Core\Facades\Session;
use Phalcon\Http\Request;
use Core\Facades\Response;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Controller as BaseController;
use App\Modules\Defaults\Auth\Model\UsersModel;
use Core\Facades\Security;

/**
 * @routeGroup("/auth")
 */
class Controller extends BaseController
{
    /**
     */
    public function indexAction($segment, $codePdam)
    {
        $pdam = PdamModel::findFirstBykode($codePdam);
        $this->view->setVars(array("pdam" => $pdam ));
        $this->view->setMainView('Defaults/Auth/login_'.$pdam->kode);
        // echo 'Defaults/Auth/login_'.$pdam->kode; die();

        // return Dispatcher::forward([
        //     'controller' => 'Defaults\\Auth\\Controller',
        //     'action'     => Request::isPost() ? 'doLogin' : 'login',
        // ]);
        // $this->view->setMainView('Defaults/Auth/index');
    }

    /**
     * @routeGet("/login")
     */
    public function loginAction($segment)
    {
        $pdam = PdamModel::findFirstBykode($segment);
        $this->view->setVars(array("pdam" => $pdam ));
        $this->view->setMainView('Defaults/Auth/login');
    }

    /**
     * @routeGet("/unauthorized")
     */
    public function unauthorizedAction()
    {
        $this->view->setMainView('Errors/unauthorized');
    }

    /**
     * @routePost("/login")
     */
    public function doLoginAction()
    {
        // var_dump($_SERVER['REQUEST_URI']);exit;
        $array = explode('/', $_SERVER['REQUEST_URI']);
        $pdam_kode = $array[2];
        $pdam = PdamModel::findFirstBykode($pdam_kode);
        $usernamePost = $this->request->getPost('username');
        $passwordPost = $this->request->getPost('password');
        $tahunPost = $this->request->getPost('tahun');
        $pdam_id = $this->request->getPost('pdam');
        
        // var_dump($this->request->getPost());exit;
        $user = UsersModel::findFirst(['conditions' => " username = '".$usernamePost."' AND pdam_id = '".$pdam_id."'"]);
        if (!isset($user->password)) {
            return Response::redirect($pdam->kode.'/auth/login?wp=true');
        }

        if (!isset($usernamePost) || !Security::checkHash($passwordPost, $user->password)) {
            // Username atau password salah
            return Response::redirect($pdam->kode.'/auth/login?wp=true');
        }
        // var_dump(Security::checkHash($passwordPost, $user->password));exit;
        $userdata = array(
            'id'            => $user->id,
            'pdam_id'       => $user->pdam_id,
            'nama'          => $user->nama,
            'username'      => $user->username,
            'password'      => $user->password,
            'id_role'       => $user->role->id,
            'role_akses'    => $user->role->role,
            'state'         => $user->state,
            'satuan_kerja_id'    => $user->role->satuan_kerja_id,
            'tahun' => $tahunPost
        );

        $pdam = PdamModel::findFirstBypdam_id($user->pdam_id);
        
        Session::set('pdam', $pdam);
        Session::set('user', $userdata);
        Session::set('tahun', $tahunPost);

        if($userdata['role_akses'] == "SATKER"){
            $intended = Session::get('intended') ?? '/'.$pdam->kode.'/pegawai/dashboard';
            Session::remove('intended');

            return Response::redirect($intended);
        }

        $intended = Session::get('intended') ?? '/'.$pdam->kode.'/dashboard';
        Session::remove('intended');

        return Response::redirect($intended);
    }

    /**
     * @routeGet('/logout')
     * @middleware('RequireUser')
     */
    public function logoutAction()
    {
        $pdam = $this->session->pdam->kode;
        Session::remove('user');
        return Response::redirect('/'.$pdam.'/auth/login');
    }

     
}