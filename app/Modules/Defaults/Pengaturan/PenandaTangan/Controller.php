<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Pengaturan\PenandaTangan;

use App\Libraries\Log;
use Phalcon\Mvc\Controller as BaseController;
use App\Modules\Defaults\Master\Hakakses\Model as RoleModel;
use Core\Facades\Response;
use App\Modules\Defaults\Middleware\Controller as MiddlewareHardController;
use Core\Facades\Request;
use Core\Paginator\DataTables\DataTable;
use App\Modules\Defaults\Pengaturan\PenandaTangan\Model as DefaultModel;
use App\Modules\Defaults\Pengaturan\PenandaTangan\ModelView as ViewModel;

/**
 * @routeGroup("/pengaturan/penanda-tangan")
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
        $laporan = Request::getPost('search_laporan');
        $nama = Request::getPost('search_nama');
        $nip = Request::getPost('search_nip');

        $builder =  $this->modelsManager->createBuilder()
        ->columns('*')
        ->from(ViewModel::class)
        ->where("1=1")
        ->andWhere("pdam_id = '$pdam_id'");

        if ($laporan) {
			$builder->andWhere("id_laporan = '$laporan'");
		}
        if ($nama) {
			$builder->andWhere("nama  LIKE '%$nama%'");
		}
        if ($nip) {
			$builder->andWhere("nip  LIKE '%$nip%'");
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
        $id_laporan = Request::getPost('id_laporan');
        $urutan = Request::getPost('urutan');
        $checkUrutan = DefaultModel::find([
            'conditions' => 'pdam_id = :pdam_id: AND master_laporan_id = :id_laporan: AND urutan = :urutan:',
            'bind' => [
                'pdam_id' => $pdam_id,
                'id_laporan' => $id_laporan,
                'urutan' => $urutan,
            ],
        ]);

        
        if(count($checkUrutan) > 0){
            $checkUrutan = DefaultModel::find([
                'conditions' => 'pdam_id = :pdam_id: AND master_laporan_id = :id_laporan: AND urutan >= :urutan:',
                'bind' => [
                    'pdam_id' => $pdam_id,
                    'id_laporan' => $id_laporan,
                    'urutan' => $urutan,
                ],
            ]);
            
            foreach($checkUrutan as $ubahUrutan){
                $updateUrutan = DefaultModel::findFirstById($ubahUrutan->id);
                $datas = [
                    'master_laporan_id'    => $ubahUrutan->master_laporan_id,
                    'text_bawah_1'         => $ubahUrutan->text_bawah_1,
                    'text_bawah_2'         => $ubahUrutan->text_bawah_2,
                    'text_atas_1'          => $ubahUrutan->text_atas_2,
                    'text_atas_2'          => $ubahUrutan->text_atas_2,
                    'urutan'               => $ubahUrutan->urutan + 1,
                    'pdam_id'              => $pdam_id,
                ];
                $updateUrutan->assign($datas);
                $results = $updateUrutan->save();
                $urutan = $urutan + 1;
            }
        }
        
        $data = [
            'master_laporan_id'   => $id_laporan,
            'text_bawah_1'          => Request::getPost('nama'),
            'text_bawah_2'     => Request::getPost('nip'),
            'text_atas_1'          => Request::getPost('text1'),
            'urutan'       => Request::getPost('urutan'),
            'text_atas_2'          => Request::getPost('text2'),
            'pdam_id'       => $pdam_id,
        ];
        // var_dump($data);exit;
        
        $create = new DefaultModel($data);
        $result = $create->save();
        $log = new Log(); 
        $log->write("Insert Data Master-Referensi Data-Penanda Tangan", $data, $result, "App\Modules\Defaults\Pengaturan\PenandaTangan\Controller", "INSERT");
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
        $urutan = Request::getPost('urutan');
        $id_laporan = Request::getPost('id_laporan');

        $checkUrutan = DefaultModel::findFirst([
            'conditions' => 'pdam_id = :pdam_id: AND master_laporan_id = :id_laporan: AND urutan = :urutan:',
            'bind' => [
                'pdam_id' => $pdam_id,
                'id_laporan' => $id_laporan,
                'urutan' => $urutan,
            ],
        ]);

        $update = DefaultModel::findFirstById($id);
        if($checkUrutan){
            $datas = [
                'master_laporan_id'    => $checkUrutan->master_laporan_id,
                'text_bawah_1'         => $checkUrutan->text_bawah_1,
                'text_bawah_2'         => $checkUrutan->text_bawah_2,
                'text_atas_1'          => $checkUrutan->text_atas_2,
                'text_atas_2'          => $checkUrutan->text_atas_2,
                'urutan'               => $update->urutan,
                'pdam_id'              => $pdam_id,
            ];
            $checkUrutan->assign($datas);
            $results = $checkUrutan->save();
        }
        // return $this->response->setJsonContent($checkUrutan == null);
        $data = [
            'master_laporan_id'    => Request::getPost('id_laporan'),
            'text_bawah_1'         => Request::getPost('nama'),
            'text_bawah_2'         => Request::getPost('nip'),
            'text_atas_1'          => Request::getPost('text1'),
            'text_atas_2'          => Request::getPost('text2'),
            'urutan'               => $urutan,
            'pdam_id'              => $pdam_id,
        ];
        
        $update->assign($data);

        $result = $update->save();

        $log = new Log(); 
        $log->write("Update Data Master-Referensi Data-Penanda Tangan", $data, $result, "App\Modules\Defaults\Pengaturan\PenandaTangan\Controller", "UPDATE");
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
        $log->write("Delete Data Master-Referensi Data-Penanda Tangan", ['id' => $id], $result, "App\Modules\Defaults\Pengaturan\PenandaTangan\Controller", "DELETE");
        // Log::write("Melakukan penghapusan master data bagian", Request::getPost(), $result, "Masterbagian", "DELETE");

        return Response::setJsonContent([
            'message' => 'Success',
        ]);
    }
}