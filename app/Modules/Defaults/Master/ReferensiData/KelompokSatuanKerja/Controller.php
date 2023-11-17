<?php 
declare(strict_types=1);

namespace App\Modules\Defaults\Master\ReferensiData\KelompokSatuanKerja;

use App\Libraries\Log;
use Phalcon\Mvc\Controller as BaseController;
use App\Modules\Defaults\Master\Hakakses\Model as RoleModel;
use Core\Facades\Response;
use App\Modules\Defaults\Middleware\Controller as MiddlewareHardController;
use Core\Facades\Request;
use Core\Paginator\DataTables\DataTable;
use App\Modules\Defaults\Master\ReferensiData\KelompokSatuanKerja\Model as DefaultModel;

/**
 * @routeGroup("/master/referensi-data/kelompok-satuan-kerja")
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
        $kelompok = Request::getPost('search_kelompok');
        $is_aktif = Request::getPost('search_is_aktif');

        $builder =  $this->modelsManager->createBuilder()
        ->columns('*')
        ->from(DefaultModel::class)
        ->where("1=1")
        ->andWhere("pdam_id = '$pdam_id'");

        if ($kelompok) {
			$builder->andWhere("kelompok LIKE '%$kelompok%'");
		}
        if ($is_aktif) {
            if($is_aktif == '1'){
                $builder->andWhere("is_aktif = 1");
            } else {
                $builder->andWhere("is_aktif = 0");
            }
		}


        $dataTables = new DataTable();
        $dataTables->fromBuilder($builder)->sendResponse();
    }

    /**
     * @routePost('/create')
     */
    public function createAction()
    {
        $pdam_id = $this->session->user['pdam_id'];

        $data = [
            'kelompok'          => Request::getPost('nama'),   
            'is_aktif'          => 1,   
            'pdam_id'       => $pdam_id,
        ];

        $create = new DefaultModel($data);
        $result = $create->save();

        $log = new Log(); 
        $log->write("Insert Data Master-Referensi Data-Kelompok Satuan Kerja", $data, $result, "App\Modules\Defaults\Master\ReferensiData\KelompokSatuanKerja\Controller", "INSERT");

        return Response::setJsonContent([
            'message' => 'Success',
        ]);
    }

    /**
     * @routePost('/update')
     */
    public function updateAction()
    {
        $pdam_id = $this->session->user['pdam_id'];
        $id = Request::getPost('id');

        $data = [
            'kelompok'          => Request::getPost('nama'),   
            'is_aktif'          => Request::getPost('is_aktif'),   
            'pdam_id'       => $pdam_id,
        ];

        $update = DefaultModel::findFirstById($id);
        $update->assign($data);

        $result = $update->save();

        $log = new Log(); 
        $log->write("Update Data Master-Referensi Data-Kelompok Satuan Kerja", $data, $result, "App\Modules\Defaults\Master\ReferensiData\KelompokSatuanKerja\Controller", "UPDATE");

        return Response::setJsonContent([
            'message' => 'Success',
        ]);
    }

    /**
     * @routePost('/delete')
     */
    public function deleteAction()
    {
        $pdam_id = $this->session->user['pdam_id'];
        
        $id = Request::get('id');

        $delete = DefaultModel::findFirstById($id);

        $result = $delete->delete();

        $log = new Log(); 
        $log->write("Delete Data Master-Referensi Data-Kelompok Satuan Kerja", ['id'=>$id], $result, "App\Modules\Defaults\Master\ReferensiData\KelompokSatuanKerja\Controller", "DELETE");

        return Response::setJsonContent([
            'message' => 'Success',
        ]);
    }
}