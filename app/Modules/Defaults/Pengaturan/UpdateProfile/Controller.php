<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Pengaturan\UpdateProfile;

use Phalcon\Mvc\Controller as BaseController;
use App\Modules\Defaults\Master\Hakakses\Model as RoleModel;
use Core\Facades\Response;
use App\Modules\Defaults\Middleware\Controller as MiddlewareHardController;
use Core\Facades\Request;
use App\Modules\Defaults\Pengaturan\User\Model as Model;
use App\Modules\Defaults\Auth\Model\PdamModel as PdamModel;
use Core\Facades\Security;

/**
 * @routeGroup("/pengaturan/ubah-profile")
 */
class Controller extends BaseController
{
    /**
     * @routeGet("/")
     */
    public function indexAction($id)
    {
        $pdam_id =  $this->session->user['pdam_id'];
        $data = PdamModel::find([
            'conditions' => 'pdam_id = :pdam_id:',
            'bind' => [
                'pdam_id' => $pdam_id,
            ],
            'bindTypes' => [
                'pdam_id' => \Phalcon\Db\Column::BIND_PARAM_INT,
            ]
        ]);
        // var_dump(json_encode($data[0])); die();
        $this->view->data = $data[0];
        $this->view->setVar('module', $id);
    }
        /**
     * @routePost("/updateProfile")
     */
    public function updateProfileAction()
    {
        $pdam_id =  $this->session->user['pdam_id'];
        $nama = Request::getPost("nama_update");
        $kotakab = Request::getPost("kota_kab_update");
        $alamat = Request::getPost("alamat_update");
        $telp = Request::getPost("telp_update");
        $file = Request::getPost("foto_profile_text");

        // var_dump($file);exit;
        $data = PdamModel::find([
            'conditions' => 'pdam_id = :pdam_id:',
            'bind' => [
                'pdam_id' => $pdam_id,
            ],
            'bindTypes' => [
                'pdam_id' => \Phalcon\Db\Column::BIND_PARAM_INT,
            ]
        ]);
        // var_dump($data[0]->nama_pdam);exit;
        $data[0]->nama_pdam = $nama;
        $data[0]->kota_kab_pdam = $kotakab;
        $data[0]->alamat_pdam = $alamat;
        $data[0]->no_telp_pdam = $telp;
        $data[0]->file_logo_pdam = $file;
        $result = $data[0]->save();

        return Response::setJsonContent([
            'message' => 'Success',
        ]);
    }

    /**
     * @routePost('/uploadFoto')
     */
    public function uploadFotoAction()
    {
        $str = mt_rand(1000000000, 9999999999);
        $date = date('Y-m-d');
        $form = (object) Request::getPost();

        // $randomstring = generateRandomstring(4);
        if ($this->request->hasFiles(true) == true) {
            $upload_dir = 'logo/';
            $namaFile = str_replace("", "",'logo' . $str . "_" . '' . $date);

            foreach ($this->request->getUploadedFiles() as $file) {
                if ($file->isUploadedFile()) {
                    $exploded = explode(".", $file->getName());
                    $extension = end($exploded);
                    $filename = $namaFile . '.' . $extension;
                    $file->moveTo($upload_dir . $filename);

                    $jason['status'] = 1;
                    $jason['filename'] = $filename;
                    echo json_encode($jason);
                } else {
                    $jason['status'] = 0;
                    echo json_encode($jason);
                }
            }
        }
        // $tanggal = date("Y_m_d_H_i_s");
        // $folder = BASEPATH . '\public\assets\logo\logo';
        // $str = mt_rand(1000000000, 9999999999);
        
        // $file_type = array('PNG', 'JPG', 'jpg', 'png', 'Jpg', 'Png', 'JPEG', 'jpeg', 'Jpeg');
        // $max_size = 10000000;

        // $file_name = $_FILES['userfile']['name'];
        // $file_size = $_FILES['userfile']['size'];
        // $element_index = $this->request->getPost('element_index');

        // $pra_extensi = (explode(".", $file_name));

        // $nama_user = $this->session->get('user')['nama'];

        // $extensi = end($pra_extensi);
        // $filename = "{$tanggal}_FotoProfile_{$str}_{$nama_user}.{$extensi}";

        // if (!in_array($extensi, $file_type)) {

        //     $result = array(
        //         "error" => 1,
        //         "message" => "Extensi File Tidak Sesuai !",
        //         "data" => array()
        //     );

        //     $this->response->setContent(json_encode($result));
        //     return $this->response;
        // } else if ($file_size > $max_size) {

        //     $result = array(
        //         "error" => 1,
        //         "message" => "Size File Melebihi Ketentuan !",
        //         "data" => array()
        //     );

        //     $this->response->setContent(json_encode($result));
        //     return $this->response;
        // } else {
        //     if (move_uploaded_file($_FILES['userfile']['tmp_name'], $folder . $filename)) {

        //         $result = array(
        //             "error" => 0,
        //             "message" => "Upload File Sukses !",
        //             "data" => array(
        //                 'filename' => $filename,
        //                 'element_index' => $element_index,
        //                 'folder'=> $folder
        //             )
        //         );
        //         $this->response->setContent(json_encode($result));
        //         return $this->response;
        //     } else {

        //         $result = array(
        //             "error" => 1,
        //             "message" => "Upload File gagal !",
        //             "data" => array()
        //         );
        //         $this->response->setContent(json_encode($result));
        //         return $this->response;
        //     }
        // }
    }
}