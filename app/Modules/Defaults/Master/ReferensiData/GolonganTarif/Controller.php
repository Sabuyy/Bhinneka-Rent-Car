<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Master\ReferensiData\GolonganTarif;

use App\Libraries\Log;
use Phalcon\Mvc\Controller as BaseController;
use App\Modules\Defaults\Master\Hakakses\Model as RoleModel;
use Core\Facades\Response;
use App\Modules\Defaults\Middleware\Controller as MiddlewareHardController;
use Core\Facades\Request;
use Core\Paginator\DataTables\DataTable;
use App\Modules\Defaults\Master\ReferensiData\GolonganTarif\Model as Model;
use App\Modules\Defaults\Master\ReferensiData\GolonganTarif\Model as DefaultModel;

/**
 * @routeGroup("/master/referensi-data/golongan-tarif")
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
        $search_kode = Request::getPost('search_kode');
        $search_nama = Request::getPost('search_nama');

        $builder = $this->modelsManager->createBuilder()
                        ->columns('*')
                        ->from(Model::class)
                        ->where("1=1")
                        ->andWhere("pdam_id = '$pdam_id'" )
                        ->andWhere("is_aktif = 1" );

        if($search_kode) {
            $builder->andWhere("kode = '$search_kode'");
        }
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
        $form = (object) Request::getPost();

        // Validasi kode unik
        $kode_exist = Model::findFirst([
            'kode = :kode:',
            'bind' => [
                'kode' => $form->kode,
            ],
        ]);

        if ($kode_exist) {
            return Response::setStatusCode(400)->setJsonContent([
                'message' => 'Kode sudah digunakan',
            ]);
        }

        // Validasi nama unik
        $nama_exist = Model::findFirst([
            'nama = :nama:',
            'bind' => [
                'nama' => $form->nama,
            ],
        ]);

        if ($nama_exist) {
            return Response::setStatusCode(400)->setJsonContent([
                'message' => 'Nama sudah digunakan',
            ]);
        }
        $data = [
            'kode' => $form->kode,
            'nama' => $form->nama,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $sessUser,
            'pdam_id' => $pdam_id,
            'is_aktif' => 1,
            
        ];

        $newKategori = new Model($data);

        $result = $newKategori->save();

        $log = new Log(); 
        $log->write("Insert Data Master-Referensi Data-Golongan Tarif", $data, $result, "App\Modules\Defaults\Master\ReferensiData\GolonganTarif\Controller", "INSERT");

        return Response::setStatusCode(201)->setJsonContent([
            'message' => 'Success',
        ]);
    }


    /**
     * @routePost("/update")
     */
    public function updateAction()
    {
        $id = Request::getPost('id');
        $form = (object) Request::getPost();
        
        // Validasi kode unik
        $kode_exist = Model::findFirst([
            'kode = :kode:',
            'bind' => [
                'kode' => $form->kode,
            ],
        ]);
        if($kode_exist){
            if ($kode_exist->id !== $id) {
                return Response::setStatusCode(400)->setJsonContent([
                    'message' => 'Kode sudah digunakan',
                ]);
            }
        }

        // Validasi nama unik
        $nama_exist = Model::findFirst([
            'nama = :nama:',
            'bind' => [
                'nama' => $form->nama,
            ],
        ]);
        
        if ($nama_exist) {
            if($nama_exist->id !== $id){
                return Response::setStatusCode(400)->setJsonContent([
                    'message' => 'Nama sudah digunakan',
                ]);              
            }
        }
        // return $this->response->setJsonContent($nama_exist);
        $data = [
            'kode' => $form->kode,
            'nama' => $form->nama,
            'created_at' => date('Y-m-d H:i:s'),
            // 'created_by' => $sessUser,
            // 'pdam_id' => $pdam_id,
        ];
        $newKategori = Model::findFirst($id);
        $newKategori -> assign($data);
        
        $result = $newKategori->save();

        $log = new Log(); 
        $log->write("Update Data Master-Referensi Data-Golongan Tarif", $data, $result, "App\Modules\Defaults\Master\ReferensiData\GolonganTarif\Controller", "UPDATE");

        return Response::setStatusCode(201)->setJsonContent([
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
        $log->write("Delete Data Master-Referensi Data-Golongan Tarif", $data, $result, "App\Modules\Defaults\Master\ReferensiData\GolonganTarif\Controller", "DELETE");

        return Response::setJsonContent([
            'message' => 'Success',
        ]);
    }
    
}