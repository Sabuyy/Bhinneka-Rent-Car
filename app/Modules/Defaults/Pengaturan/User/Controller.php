<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Pengaturan\User;

use Phalcon\Mvc\Controller as BaseController;
use App\Modules\Defaults\Master\Hakakses\Model as RoleModel;
use Core\Facades\Response;
use App\Modules\Defaults\Middleware\Controller as MiddlewareHardController;
use Core\Facades\Request;
use Core\Paginator\DataTables\DataTable;
use App\Modules\Defaults\Pengaturan\User\Model as Model;
use App\Modules\Defaults\Pengaturan\User\VwModel as VwModel;
use Core\Facades\Security;

/**
 * @routeGroup("/pengaturan/user")
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
     * @routeGet("/datatable")
     * @routePost("/datatable")
     */
    public function datatableAction()
    {
        $pdam_id = $this->session->user['pdam_id'];
        $search_nama = Request::getPost('search_nama');
        $search_username = Request::getPost('search_username');
        $id_role_search = Request::getPost('id_role_search');

        $builder = $this->modelsManager->createBuilder()
                        ->columns('*')
                        ->from(VwModel::class)
                        ->where("1=1")
                        ->andWhere("pdam_id = '$pdam_id'");

        if($search_nama) {
            $builder->andWhere("nama LIKE '%$search_nama%'");
        }

        if($search_username) {
            $builder->andWhere("username LIKE '%$search_username%'");
        }

        if($id_role_search) {
            $builder->andWhere("id_role = '$id_role_search'");
        }

        $dataTables = new DataTable();
        $dataTables->fromBuilder($builder)->sendResponse();
    }

    /**
     * @routeGet("/detail")
     */
    public function detailAction()
    {

    }

    /**
     * @routePost("/store")
     */
    public function storeAction()
    {
        $pdam_id = $this->session->user['pdam_id'];
        $sessUser = $this->session->user['nama'];
        $form = (object) Request::getPost();
        $checkUsername = Model::query()->where("username = '{$form->username}'")->execute()->toArray();
        if (count($checkUsername) == 0) {
            $newUser = new Model([
                'pdam_id'    => $this->session->user['pdam_id'],
                'username' => Request::getPost('username'),
                'nama' => Request::getPost('nama'),
                'password' => Security::hash(Request::getPost('password'), 10),
                'id_role'    => Request::getPost('id_role'),
                'satuan_kerja_id'    => Request::getPost('satuan_kerja_id'),
            ]);
            $result = $newUser->save();

            return Response::setStatusCode(201)->setJsonContent([
                'message' => 'Success',
            ]);
        } else {
            return Response::setStatusCode(403)->setJsonContent([
                'message' => 'Username telah digunakan',
            ]);
        }
    }

    /**
     * @routePost("/update")
     */
    public function updateAction()
    {
        $id = Request::getPost('id');
        $update = Model::findFirst($id);
        $update->username = Request::getPost('username');
        $update->nama = Request::getPost('nama');
        $update->password = Security::hash(Request::getPost('password'), 10);
        $update->id_role = Request::getPost('id_role');
        $update->satuan_kerja_id = Request::getPost('satuan_kerja_id');

        $update->save();

        return Response::setJsonContent([
            'message' => 'Success',
        ]);
    }

    /**
     * @routePost("/delete")
     */
    public function deleteAction()
    {
        $id = Request::get('id');
        $update = Model::findFirst($id);

        $update->delete();

        return Response::setJsonContent([
            'message' => 'Success',
        ]);
    }
    
}