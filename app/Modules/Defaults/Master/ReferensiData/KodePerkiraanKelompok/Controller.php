<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Master\ReferensiData\KodePerkiraanKelompok;

use App\Libraries\Log;
use Phalcon\Mvc\Controller as BaseController;
use App\Modules\Defaults\Master\Hakakses\Model as RoleModel;
use Core\Facades\Response;
use App\Modules\Defaults\Middleware\Controller as MiddlewareHardController;
use Core\Facades\Request;
use Core\Paginator\DataTables\DataTable;
use App\Modules\Defaults\Master\ReferensiData\KodePerkiraanKelompok\Model as DefaultModel;
use App\Modules\Defaults\Master\ReferensiData\KodePerkiraanKelompok\ModelView as ViewModel;

/**
 * @routeGroup("/master/referensi-data/kode-perkiraan-kelompok")
 */
class Controller extends BaseController
{
    /**
     * @routeGet("/")
     */
    public function indexAction($id)
    {
        $this->view->setVar('module', $id);
        $this->view->setMainView('Defaults/Master/ReferensiData/KodePerkiraanKelompok/indexnew');
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
        $id_induk = Request::getPost('search_id_induk');

        $builder =  $this->modelsManager->createBuilder()
        ->columns('*')
        ->from(ViewModel::class)
        ->where("1=1")
        ->andWhere("pdam_id = '$pdam_id'");

        if ($kode) {
			$builder->andWhere("kode LIKE '%$kode%'");
		}
        if ($nama) {
			$builder->andWhere("nama  LIKE '%$nama%'");
		}
        if ($id_induk) {
			$builder->andWhere("parent_id = '$id_induk'");
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
        $golongan = Request::getPost('golongan');
        
        if($parent){
            $induk = $parent;
        }else{
            $induk = null;
        }

        $data = [
            'parent_id'     => $induk, 
            'golongan_id'     => $golongan, 
            'kode'          => Request::getPost('kode'),
            'nama'          => Request::getPost('nama'),
            'created_at'    => date('Y-m-d H:i:s'),
            'created_by'    => $sessUser,
            'pdam_id'       => $pdam_id,
        ];
        
        $create = new DefaultModel($data);
        $result = $create->save();

        $log = new Log(); 
        $log->write("Insert Data Master-Referensi Data-Kode Perkiraan Kelompok", $data, $result, "App\Modules\Defaults\Master\ReferensiData\KodePerkiraanKelompok\Controller", "INSERT");
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
        $golongan = Request::getPost('golongan');

        if($parent){
            $induk = $parent;
        }else{
            $induk = null;
        }

        $data = [
            'parent_id'     => $induk, 
            'golongan_id'     => $golongan,
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
        $log->write("Update Data Master-Referensi Data-Kode Perkiraan Kelompok", $data, $result, "App\Modules\Defaults\Master\ReferensiData\KodePerkiraanKelompok\Controller", "UPDATE");
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
        $log->write("Delete Data Master-Referensi Data-Kode Perkiraan Kelompok", ['id'=>$id], $result, "App\Modules\Defaults\Master\ReferensiData\KodePerkiraanKelompok\Controller", "DELETE");
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

        $query = "CALL sp_singkron_akun_kelompok($pdam_id)";
        $result = $this->db->execute($query);

        return Response::setJsonContent([
            'message' => 'Success',
        ]);
    }   

}