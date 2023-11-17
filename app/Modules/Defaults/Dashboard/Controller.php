<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Dashboard;

use Phalcon\Mvc\Controller as BaseController;
use App\Modules\Defaults\Master\Hakakses\Model as RoleModel;
use Core\Facades\Response;
use Core\Facades\Request;
use App\Modules\Defaults\Middleware\Controller as MiddlewareHardController;

/**
 * @routeGroup("/dashboard")
 */
class Controller extends MiddlewareHardController
{
    /**
     * @routeGet("/")
     */
    public function indexAction($id)
    {
        $this->view->setVar('module', $id);
        $this->view->tahun = $this->session->user['tahun'];
    }

    /**
     * @routeGet("/proyeksi-laba-rugi")
     */
    public function proyeksiLabaRugiAction()
    {
        $tahunSekarang = $this->session->user['tahun'];
        $pdam_id = $this->session->user['pdam_id'];
        $jenis = Request::get("jenis");
        if($jenis == "RKA"){
            $sql = "CALL sp_dashboard_proyeksi_laba_rugi($pdam_id, $tahunSekarang,'RKA')";
        }
        else if($jenis == "RKAP"){
            $sql = "CALL sp_dashboard_proyeksi_laba_rugi($pdam_id, $tahunSekarang,'RKAP')";
        }
        
        $result = $this->db->fetchAll($sql);

        return Response::setJsonContent([
            'data' => $result
        ]);
    }

    /**
     * @routeGet("/jumlah-investasi")
     */
    public function jumlahInvestasiAction()
    {
        $tahunSekarang = $this->session->user['tahun'];
        $pdam_id = $this->session->user['pdam_id'];
        $jenis = Request::get("jenis");
        // var_dump($jenis);exit;
        if($jenis == "RKA"){
            $sql = "CALL sp_dashboard_jumlah_investasi($pdam_id, $tahunSekarang,'RKA')";
        }
        else if($jenis == "RKAP"){
            $sql = "CALL sp_dashboard_jumlah_investasi($pdam_id, $tahunSekarang,'RKAP')";
        }
        
        $result = $this->db->fetchOne($sql);

        return Response::setJsonContent([
            'data' => $result
        ]);
    }

    /**
     * @routeGet("/jumlah-biaya")
     */
    public function jumlahBiayaAction()
    {
        $tahunSekarang = $this->session->user['tahun'];
        $pdam_id = $this->session->user['pdam_id'];
        $jenis = Request::get('jenis');
        if($jenis == "RKA"){
            $sql = "CALL sp_dashboard_jumlah_biaya($pdam_id, $tahunSekarang,'RKA')";
        }
        else if($jenis == "RKAP"){
            $sql = "CALL sp_dashboard_jumlah_biaya($pdam_id, $tahunSekarang,'RKAP')";
        }
        
        $result = $this->db->fetchOne($sql);

        return Response::setJsonContent([
            'data' => $result
        ]);
    }

    /**
     * @routeGet("/jumlah-sl")
     */
    public function jumlahSLAction()
    {
        $tahunSekarang = $this->session->user['tahun'];
        $pdam_id = $this->session->user['pdam_id'];
        $jenis = Request::get('jenis');
        $sql = "CALL sp_dashboard_sl($pdam_id, $tahunSekarang,'$jenis')";
        $result = $this->db->fetchAll($sql);
        return Response::setJsonContent($result);
    }

    /**
     * @routeGet("/data-terverifikasi")
     */
    public function dataTerverifikasiAction()
    {
        $tahunSekarang = $this->session->user['tahun'];
        $pdam_id = $this->session->user['pdam_id'];
        $jenis = Request::get('jenis');
        if($jenis == "RKA"){
            $sql = "CALL sp_dashboard_data_terverifikasi($pdam_id, $tahunSekarang,'RKA')";
        }
        else if($jenis == "RKAP"){
            $sql = "CALL sp_dashboard_data_terverifikasi($pdam_id, $tahunSekarang,'RKAP')";
        }
        
        $result = $this->db->fetchAll($sql);

        return Response::setJsonContent([
            'data' => $result
        ]);
    }

    /**
     * @routeGet("/pendapatan")
     */
    public function pendapatanAction()
    {
        $tahunSekarang = $this->session->user['tahun'];
        $pdam_id = $this->session->user['pdam_id'];
        $jenis = Request::get('jenis');
        if($jenis == "RKA"){
            $sql = "CALL sp_dashboard_pendapatan($pdam_id, $tahunSekarang, 'RKA')";
        }
        else if($jenis == "RKAP"){
            $sql = "CALL sp_dashboard_pendapatan($pdam_id, $tahunSekarang, 'RKAP')";
        }

        $result = $this->db->fetchAll($sql);

        $tmp_header = array();
        $mainData = array();
        foreach ($result as $head) {
            if (!in_array($head['kode_kelompok'], $tmp_header)) {
                $data["total"] = 0;
                $data["kode_kelompok"]  = $head['kode_kelompok'];
                $data["kelompok"]       = $head['kelompok'];
                $data["data"]           = array();
                foreach ($result as $pendapatan) {
                    if($head['kode_kelompok'] == $pendapatan["kode_kelompok"]){
                        array_push($data["data"], $pendapatan);
                        $data["total"]         += $pendapatan['jumlah'];
                    }
                }
                array_push($tmp_header, $head['kode_kelompok']);
                array_push($mainData, $data);
            }
        }
        return Response::setJsonContent($mainData);
    }

    /**
     * @routeGet("/bar-biaya-pendapatan")
     */
    public function barBiayaPendapatanAction()
    {
        $tahunSekarang = $this->session->user['tahun'];
        $pdam_id = $this->session->user['pdam_id'];
        $jenis = Request::get('jenis');
        if($jenis == "RKA"){
            $sql = "CALL sp_dashboard_biaya_pendapatan($pdam_id, $tahunSekarang, 'RKA')";
        }
        else if($jenis == "RKAP"){
            $sql = "CALL sp_dashboard_biaya_pendapatan($pdam_id, $tahunSekarang, 'RKAP')";
        }

        $result = $this->db->fetchAll($sql);

        return Response::setJsonContent([
            'data' => $result
        ]);
    }

    /**
     * @routeGet("/pie-anggaran-biaya-per-satker")
     */
    public function pieAnggaranBiayaPerSatkerAction()
    {
        $tahunSekarang = $this->session->user['tahun'];
        $pdam_id = $this->session->user['pdam_id'];
        $jenis = Request::get('jenis');
        if($jenis == "RKA"){
            $sql = "CALL sp_dashboard_alokasi_anggaran_satker($pdam_id, $tahunSekarang, 'RKA')";
        }
        else if($jenis == "RKAP"){
            $sql = "CALL sp_dashboard_alokasi_anggaran_satker($pdam_id, $tahunSekarang, 'RKAP')";
        }
        $result = $this->db->fetchAll($sql);

        return Response::setJsonContent([
            'data' => $result
        ]);
    }

    /**
     * @routeGet("/estimasi-anggaran-biaya")
     */
    public function estimasiAnggaranBiayaAction()
    {
        $tahunSekarang = $this->session->user['tahun'];
        $pdam_id = $this->session->user['pdam_id'];
        $jenis = Request::get('jenis');
        if($jenis == "RKA"){
            $sql = "CALL sp_dashboard_anggaran_biaya($pdam_id, $tahunSekarang,'RKA')";
        }
        else if($jenis == "RKAP"){
            $sql = "CALL sp_dashboard_anggaran_biaya($pdam_id, $tahunSekarang, 'RKAP')";
        }

        $result = $this->db->fetchAll($sql);

        return Response::setJsonContent([
            'data' => $result
        ]);
    }

    /**
     * @routeGet("/produksi-distribusi")
     */
    public function produksiDistribusiAction()
    {
        $tahunSekarang = $this->session->user['tahun'];
        $pdam_id = $this->session->user['pdam_id'];
        $jenis = Request::get('jenis');
        if($jenis == "RKA"){
            $sql = "CALL sp_dashboard_produksi_distribusi($pdam_id, $tahunSekarang,'RKA')";
        }
        else if($jenis == "RKAP"){
            $sql = "CALL sp_dashboard_produksi_distribusi($pdam_id, $tahunSekarang, 'RKAP')";
        }

        $result = $this->db->fetchOne($sql);

        return Response::setJsonContent([
            'data' => $result
        ]);
    }

    /**
     * @routePost("/produksi-distribusi-bulan")
     */
    public function produksiDistribusiBulanAction()
    {
        $tahunSekarang = $this->session->user['tahun'];
        $pdam_id = $this->session->user['pdam_id'];
        $bulan = Request::getPost('bulan');
        $jenis = Request::getPost('jenis');
        if($jenis == "RKA"){
            $sql = "CALL sp_dashboard_produksi_distribusi_bulan($pdam_id, $tahunSekarang, $bulan,'RKA')";
        }
        else if($jenis == "RKAP"){
            $sql = "CALL sp_dashboard_produksi_distribusi_bulan($pdam_id, $tahunSekarang, $bulan,'RKAP')";
        }

        $result = $this->db->fetchOne($sql);

        return Response::setJsonContent([
            'data' => $result
        ]);
    }

    /**
     * @routePost("/arus-kas-tahun")
     */
    public function arusKasTahunAction()
    {
        $tahunSekarang = $this->session->user['tahun'];
        $pdam_id = $this->session->user['pdam_id'];
        $bulan = Request::getPost('bulan');
        $jenis = Request::get('jenis');

        if($jenis == "RKA"){
            $sql = "CALL sp_dashboard_arus_kas_tahun($tahunSekarang, $pdam_id,'RKA')";
        }
        else if($jenis == "RKAP"){
            $sql = "CALL sp_dashboard_arus_kas_tahun($tahunSekarang, $pdam_id,'RKAP')";
        }
       
        $result = $this->db->fetchOne($sql);

        return Response::setJsonContent([
            'data' => $result
        ]);
    }

    /**
     * @routePost("/arus-kas-bulan")
     */
    public function arusKasBulanAction()
    {
        $tahunSekarang = $this->session->user['tahun'];
        $pdam_id = $this->session->user['pdam_id'];
        $bulan = Request::getPost('bulan');
        $jenis = Request::getPost('jenis');

        if($jenis == "RKA"){
            $sql = "CALL sp_dashboard_arus_kas_bulan($tahunSekarang, $pdam_id, $bulan, 'RKA')";
        }
        else if($jenis == "RKAP"){
            $sql = "CALL sp_dashboard_arus_kas_bulan($tahunSekarang, $pdam_id, $bulan, 'RKAP')";
        }

        $result = $this->db->fetchOne($sql);
        // var_dump($result["penerimaan_kas"]);exit;
        return Response::setJsonContent([
            'data' => $result
        ]);
    }
}