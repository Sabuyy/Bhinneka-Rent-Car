<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Master\ReferensiData\SatuanKerja;

use App\Libraries\Log;
use Phalcon\Mvc\Controller as BaseController;
use App\Modules\Defaults\Master\Hakakses\Model as RoleModel;
use Core\Facades\Response;
use App\Modules\Defaults\Middleware\Controller as MiddlewareHardController;
use Core\Facades\Request;
use Core\Paginator\DataTables\DataTable;
use App\Modules\Defaults\Master\ReferensiData\SatuanKerja\Model as DefaultModel;
use App\Modules\Defaults\Master\ReferensiData\SatuanKerja\ModelView as ViewModel;

/**
 * @routeGroup("/master/referensi-data/satuan-kerja")
 */
class Controller extends MiddlewareHardController
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
        $kode = Request::getPost('search_kode');
        $nama = Request::getPost('search_nama');
        $id_kelompok = Request::getPost('search_id_kelompok');

        $builder =  $this->modelsManager->createBuilder()
        ->columns('*')
        ->from(ViewModel::class)
        ->where("1=1")
        ->andWhere("pdam_id = '$pdam_id'");
        // ->andWhere("status_kelompok = 1");

        if ($kode) {
			$builder->andWhere("kode LIKE '%$kode%' or long_kode LIKE '%$kode%'");
		}
        if ($nama) {
			$builder->andWhere("nama  LIKE '%$nama%'");
		}
        if ($id_kelompok) {
			$builder->andWhere("kelompok_id = '$id_kelompok'");
		}

        $dataTables = new DataTable();
        $dataTables->fromBuilder($builder)->sendResponse();
    }
    
    /**
     * @routePost("/create")
     */
    public function createAction()
    {
        $pdam_id = $this->session->user['pdam_id'];
        $data = [
            'kelompok_id'   => Request::getPost('id_kelompok'),
            'kode'          => Request::getPost('kode'),
            'long_kode'     => Request::getPost('long_kode'),
            'nama'          => Request::getPost('nama'),
            'pdam_id'       => $pdam_id,
        ];
        $create = new DefaultModel($data);
        $result = $create->save();
        $log = new Log(); 
        $log->write("Insert Data Master-Referensi Data-Satuan Kerja", $data, $result, "App\Modules\Defaults\Master\ReferensiData\SatuanKerja\Controller", "INSERT");
        // Log::write("Melakukan penambahan master data bagian", Request::getPost(), $result, "Masterbagian", "INSERT");

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
        $pdam_id = $this->session->user['pdam_id'];
        $data = [
            'kelompok_id'   => Request::getPost('id_kelompok'),
            'kode'          => Request::getPost('kode'),
            'long_kode'     => Request::getPost('long_kode'),
            'nama'          => Request::getPost('nama'),
            'pdam_id'       => $pdam_id,
        ];
        $update = DefaultModel::findFirstById($id);
        $update->assign($data);

        $result = $update->save();

        $log = new Log(); 
        $log->write("Update Data Master-Referensi Data-Satuan Kerja", $data, $result, "App\Modules\Defaults\Master\ReferensiData\SatuanKerja\Controller", "UPDATE");
        // Log::write("Melakukan perubahan master data bagian", Request::getPost(), $result, "Masterbagian", "INSERT");

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
        $delete = DefaultModel::findFirstById($id);
        $result = $delete->delete();

        $log = new Log(); 
        $log->write("Delete Data Master-Referensi Data-Satuan Kerja", ['id' => $id], $result, "App\Modules\Defaults\Master\ReferensiData\SatuanKerja\Controller", "DELETE");
        // Log::write("Melakukan penghapusan master data bagian", Request::getPost(), $result, "Masterbagian", "DELETE");

        return Response::setJsonContent([
            'message' => 'Success',
        ]);
    }
}