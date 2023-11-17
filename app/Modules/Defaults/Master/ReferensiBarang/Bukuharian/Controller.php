<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Master\ReferensiBarang\Bukuharian;

use App\Libraries\Log;
use Phalcon\Mvc\Controller as BaseController;
use App\Modules\Defaults\Master\Hakakses\Model as RoleModel;
use Core\Facades\Response;
use App\Modules\Defaults\Middleware\Controller as MiddlewareHardController;
use Core\Facades\Request;
use Core\Paginator\DataTables\DataTable;
use App\Modules\Defaults\Master\ReferensiBarang\Bukuharian\Model as Model;

/**
 * @routeGroup("/master/referensi-barang/Bukuharian")
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
        $search_judul = Request::getPost('search_judul');

        $builder = $this->modelsManager->createBuilder()
                        ->columns('*')
                        ->from(VwModel::class)
                        ->where("1=1")
                        ->andWhere("pdam_id = '$pdam_id'");

        if($search_judul) {
            $builder->andWhere("judul LIKE '%$search_judul%'");
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
            'absen'         => Request::getPost('absen'),
            'tanggal'       => date('Y-m-d'),
            'hari'          => date('Y-m-d'),
            'judul'         => Request::getPost('judul'),
            'uraian'        => Request::getPost('uraian'),
            'lokasi'        => Request::getPost('lokasi'),
            'pdam_id'       => $pdam_id,
        ];
        $create = new Model($data);
        $result = $create->save();

        $log = new Log(); 
        $log->write("Insert Data Master-Referensi Barang-Bukuharian", $data, $result, "App\Modules\Defaults\Master\ReferensiBarang\Bukuharian\Controller", "INSERT");
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
            'absen'         => Request::getPost('absen'),
            'judul'         => Request::getPost('judul'),
            'uraian'        => Request::getPost('uraian'),
            'lokasi'        => Request::getPost('lokasi'),
        ];
        $update = Model::findFirst($id);
        $update->assign($data);

        $result = $update->save();
        $log = new Log(); 
        $log->write("Update Data Master-Referensi Barang-Kategori", $data, $result, "App\Modules\Defaults\Master\ReferensiBarang\Kategori\Controller", "UPDATE");
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
            'id'          => Request::get('id')
        ];
        $delete = Model::findFirst($id);
        $result = $delete->delete();

        $log = new Log(); 
        $log->write("Delete Data Master-Referensi Barang-Kategori", $data, $result, "App\Modules\Defaults\Master\ReferensiBarang\Kategori\Controller", "DELETE");

        return Response::setJsonContent([
            'message' => 'Success',
        ]);
    }
    
}