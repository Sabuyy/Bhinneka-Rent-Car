<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Master\ReferensiData\KodePerkiraanSatuanKerja;

use App\Libraries\Log;
use Phalcon\Mvc\Controller as BaseController;
use App\Modules\Defaults\Master\Hakakses\Model as RoleModel;
use Core\Facades\Response;
use App\Modules\Defaults\Middleware\Controller as MiddlewareHardController;
use Core\Facades\Request;
use Core\Paginator\DataTables\DataTable;
use App\Modules\Defaults\Master\ReferensiData\KodePerkiraanSatuanKerja\Model as DefaultModel;
use App\Modules\Defaults\Master\ReferensiData\KodePerkiraanSatuanKerja\ModelView as ViewModel;
use App\Modules\Defaults\Master\ReferensiData\KodePerkiraanSatuanKerja\ModelViewStatus as ViewStatusModel;
use App\Modules\Defaults\Master\ReferensiData\KodePerkiraan\Model as ModelPerkiraan;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

/**
 * @routeGroup("/master/referensi-data/kode-perkiraan-satuan-kerja")
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
        $pdam_id = $this->session->get('user')['pdam_id']; 
        $satuankerja = $this->request->getPost('satuankerja');

        $draw = $this->request->getPost('draw', 'int');
        $start = $this->request->getPost('start', 'int');
        $length = $this->request->getPost('length', 'int');
        
        $db = $this->di->get('db');
        $offset = $start;
        $sql = "
            SELECT
                m.id,
                m.pdam_id,
                m.kode,
                m.nama,
                m.nama_gol,
                m.nama_akun_kelompok,
                s.* 
            FROM
                vw_master_akun_perkiraan m
                LEFT JOIN ( 
                SELECT 
                perkiraan_id, 
                id_satuan_kerja, 
                is_aktif 
                FROM master_akun_perkiraan_status 
                WHERE pdam_id = $pdam_id AND id_satuan_kerja = '$satuankerja' ) s 
                ON m.id = s.perkiraan_id 
            WHERE
                m.is_hapus = 0 
                AND pdam_id = $pdam_id
                LIMIT 0 OFFSET 0
        ";
        // Raw SQL query
        if($satuankerja){
            $sql = "
            SELECT
            m.id,
            m.pdam_id,
            m.kode,
            m.nama,
            m.nama_gol,
            m.nama_akun_kelompok,
            s.* 
        FROM
            vw_master_akun_perkiraan m
            LEFT JOIN ( 
            SELECT 
            perkiraan_id, 
            id_satuan_kerja, 
            is_aktif 
            FROM master_akun_perkiraan_status 
            WHERE pdam_id = $pdam_id AND id_satuan_kerja = '$satuankerja' ) s 
            ON m.id = s.perkiraan_id 
        WHERE
            m.is_hapus = 0 
            AND pdam_id = $pdam_id
            LIMIT $length OFFSET $offset
        ";
        }
        

        $bindParams = [
            'pdam_id' => $pdam_id,
            'satuankerja' => $satuankerja,
        ];
        

        $result = $this->db->fetchAll($sql);

        $totalRecords = $this->db->fetchOne("SELECT COUNT(*) as count FROM master_akun_perkiraan WHERE is_hapus = 0");

        $response = [
            'draw' => $draw,
            'recordsTotal' => $totalRecords['count'],
            'recordsFiltered' => count($result),
            'data' => $result,
        ];

        return $this->response->setJsonContent($response);
    }
    /**
 * @routePost('/batchInsert')
 * @routeGet('/batchInsert')
 */
public function batchFinishedAction()
{
    $this->response->setContentType('application/json', 'UTF-8');
    // var_dump($this->request->getPost());exit;
    $data = $this->request->getPost();
    $data_checked = array();
    $data_unchecked = array();

    foreach ($data as $key => $value) {
        if ($key === "datas_checked") {
            $data_checked = $value;
        } elseif ($key === "datas_unchecked") {
            $data_unchecked = $value;
        }
    }

    $of_code = $data_checked["of_code"];
    $type = $data_checked["type"];

    $pdam_id = $this->session->user['pdam_id'];

   
    $result = array();
    foreach ($data_checked["perkiraan_id"] as $value) {
        $result = $this->verified($value, $of_code);
        if($result == false){
            $create = new DefaultModel([
                'perkiraan_id'     => $value, 
                'id_satuan_kerja'  => $of_code,
                'is_aktif'         => 1,
                'pdam_id'       => $pdam_id,
            ]);
            $create->save();
        } else if ($result == true) {
            $is_aktif = $this->checkActive($value, $of_code);
            if($is_aktif == false){
                $update = DefaultModel::findFirst([
                    'conditions' => 'perkiraan_id = :perkiraan_id: AND id_satuan_kerja = :of_code:',
                    'bind' => [
                        'perkiraan_id' => $value,
                        'of_code' => $of_code
                    ]
                    
                ]);
                $update->is_aktif = 1;
                $update->save();
            }
        }
    }
    // var_dump($data_unchecked);exit;
    if (!empty($data_unchecked)) {
        foreach ($data_unchecked["perkiraan_id"] as $value) {
            $result = $this->verified($value, $of_code);
                if($result == true){
                    $update = DefaultModel::findFirst([
                        'conditions' => 'perkiraan_id = :perkiraan_id: AND id_satuan_kerja = :of_code:',
                        'bind' => [
                            'perkiraan_id' => $value,
                            'of_code' => $of_code
                        ]
                        
                    ]);
                    $update->is_aktif = 0;
                    $update->save();
                }
            }
    }

   

        // Log::write("Melakukan penambahan kode perkiraan", $this->request->getPost(), true, "KodePerkiraanSatuanKerja", "UPDATE");
        $result = array(
            "error" => 0,
            "message" => "Gagal",
            "data" => array($result)
        );
   
    $this->response->setContent(json_encode($result));
    return $this->response;
}

private function verified($acc_id, $of_code)
{
    $model = new DefaultModel(); 
    $result = $model->findFirst([
        'conditions' => 'perkiraan_id = :perkiraan_id: AND id_satuan_kerja = :of_code:',
        'bind' => [
            'perkiraan_id' => $acc_id,
            'of_code' => $of_code
        ]
        
    ]);
    
    
    if ($result) {
        return true;
    } else {
        return false;
    }
    
}

private function checkActive($acc_id, $of_code )
{
    $model = new DefaultModel(); 
    $result = $model->findFirst([
        'conditions' => 'perkiraan_id = :perkiraan_id: AND id_satuan_kerja = :of_code:',
        'bind' => [
            'perkiraan_id' => $acc_id,
            'of_code' => $of_code
        ]
    ]);
    
    
    if ($result->is_aktif == 1) {
        return true;
    } else {
        return false;
    }
    
}



    


    
}