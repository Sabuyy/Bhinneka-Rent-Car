<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Master\ReferensiBarang\Satuan;

use App\Libraries\Log;
use Phalcon\Mvc\Controller as BaseController;
use App\Modules\Defaults\Master\Hakakses\Model as RoleModel;
use Core\Facades\Response;
use App\Modules\Defaults\Middleware\Controller as MiddlewareHardController;
use Core\Facades\Request;
use Core\Paginator\DataTables\DataTable;
use App\Modules\Defaults\Master\ReferensiBarang\Satuan\Model as Model;

/**
 * @routeGroup("/master/referensi-barang/satuan")
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

        $builder = $this->modelsManager->createBuilder()
                        ->columns('*')
                        ->from(Model::class)
                        ->where("1=1")
                        ->andWhere("pdam_id = '$pdam_id'");

        if($search_nama) {
            $builder->andWhere("nama LIKE '%$search_nama%'");
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
        
        $data = [ 
            'nama'          => Request::getPost('nama'),
            'created_at'    => date('Y-m-d H:i:s'),
            'created_by'    => $sessUser,
            'pdam_id'       => $pdam_id,
        ];

        $create = new Model($data);
        $result = $create->save();

        $log = new Log(); 
        $log->write("Insert Data Master-Referensi Barang-Satuan", $data, $result, "App\Modules\Defaults\Master\ReferensiBarang\Satuan\Controller", "INSERT");

        return Response::setJsonContent([
            'message' => 'Success',
        ]);
    }

    /**
     * @routePost("/update")
     */
    public function updateAction()
    {
        $id = Request::getPost('id');

        $data = [
            'nama'          => Request::getPost('nama'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $update = Model::findFirst($id);
        $update->assign($data);

        $result = $update->save();

        $log = new Log(); 
        $log->write("Update Data Master-Referensi Barang-Satuan", $data, $result, "App\Modules\Defaults\Master\ReferensiBarang\Satuan\Controller", "UPDATE");

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
        $data = [
            'id' => $id
        ];
        $delete = Model::findFirst($id);

        $result = $delete->delete();

        $log = new Log(); 
        $log->write("Delete Data Master-Referensi Barang-Satuan", $data, $result, "App\Modules\Defaults\Master\ReferensiBarang\Satuan\Controller", "DELETE");

        return Response::setJsonContent([
            'message' => 'Success',
        ]);
    }
    
}