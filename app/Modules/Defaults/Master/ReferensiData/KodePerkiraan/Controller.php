<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Master\ReferensiData\KodePerkiraan;

use App\Libraries\Log;
use Phalcon\Mvc\Controller as BaseController;
use App\Modules\Defaults\Master\Hakakses\Model as RoleModel;
use Core\Facades\Response;
use App\Modules\Defaults\Middleware\Controller as MiddlewareHardController;
use Core\Facades\Request;
use Core\Paginator\DataTables\DataTable;
use App\Modules\Defaults\Master\ReferensiData\KodePerkiraan\Model as DefaultModel;
use App\Modules\Defaults\Master\ReferensiData\KodePerkiraan\ModelView as ViewModel;

/**
 * @routeGroup("/master/referensi-data/kode-perkiraan")
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
        $induk = Request::getPost('search_id_induk');
        $nama_kode = Request::getPost('nama_kode');
        $prefix = Request::get('tipe');
        $idSatker = Request::get('id');

        $builder =  $this->modelsManager->createBuilder()
        ->columns('*')
        ->from(ViewModel::class)
        ->where("1=1")
        ->andWhere("pdam_id = '$pdam_id'");

        if ($prefix) {
			$builder->andWhere("kode LIKE '".$prefix."%'");
		}
        if ($kode) {
			$builder->andWhere("kode LIKE '%$kode%'");
		}
        if ($nama) {
			$builder->andWhere("nama  LIKE '%$nama%'");
		}
        if ($induk) {
			$builder->andWhere("parent_id = '$induk'");
		}
        if ($idSatker) {
			$builder->andWhere("kode LIKE '%$kode%'");
		}
        if ($nama_kode) {
			$builder->andWhere("nama LIKE '%$nama_kode%' OR kode LIKE '%$nama_kode%'");
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
        $sessUser = $this->session->user['nama'];
        $parent = Request::getPost('induk');
        $kelompok = Request::getPost('kelompok');
        
        if($parent){
            $induk = $parent;
        }else{
            $induk = null;
        }

        $data = [
            'parent_id'     => $induk, 
            'kelompok_id'     => $kelompok, 
            'kode'          => Request::getPost('kode'),
            'nama'          => Request::getPost('nama'),
            'created_at'    => date('Y-m-d H:i:s'),
            'created_by'    => $sessUser,
            'pdam_id'       => $pdam_id,
        ];

        $create = new DefaultModel($data);
        $result = $create->save();
        $log = new Log(); 
        $log->write("Insert Data Master-Referensi Data-Kode Perkiraan", $data, $result, "App\Modules\Defaults\Master\ReferensiData\KodePerkiraan\Controller", "INSERT");
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
        $sessUser = $this->session->user['nama'];

        $parent = Request::getPost('induk');
        $kelompok = Request::getPost('kelompok');

        if($parent){
            $induk = $parent;
        }else{
            $induk = null;
        }

        $data = [
            'parent_id'     => $induk, 
            'kelompok_id'     => $kelompok,
            'kode'          => Request::getPost('kode'),
            'nama'          => Request::getPost('nama'),
            'created_at'    => date('Y-m-d H:i:s'),
            'created_by'    => $sessUser,
            'pdam_id'       => $pdam_id,
        ];

        $update = DefaultModel::findFirstById($id);
        $update->assign($data);

        $result = $update->save();
        $log = new Log(); 
        $log->write("Update Data Master-Referensi Data-Kode Perkiraan", $data, $result, "App\Modules\Defaults\Master\ReferensiData\KodePerkiraan\Controller", "UPDATE");
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
        $log->write("Delete Data Master-Referensi Data-Kode Perkiraan", ['id'=>$id], $result, "App\Modules\Defaults\Master\ReferensiData\KodePerkiraan\Controller", "DELETE");
        
        // Log::write("Melakukan penghapusan master data bagian", Request::getPost(), $result, "Masterbagian", "DELETE");

        return Response::setJsonContent([
            'message' => 'Success',
        ]);
    }

    /**
     * @routePost("/sinkron")
     */
    public function sinkronAction()
    {
        $pdam_id = $this->session->user['pdam_id'];

        $query = "CALL sp_singkron_akun_perkiraan($pdam_id)";
        $result = $this->db->execute($query);

        return Response::setJsonContent([
            'message' => 'Success',
        ]);
    }

}