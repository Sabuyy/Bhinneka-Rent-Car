<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Referensi;

use Phalcon\Mvc\Controller as BaseController;
use App\Modules\Defaults\ModelStandAlone\ModelKelompokSatker;
use App\Modules\Defaults\Master\ReferensiData\KodePerkiraanGolongan\Model as ModelGolongan;
use App\Modules\Defaults\Master\ReferensiData\KodePerkiraanGolongan\ModelView as ViewModelGolongan;
use App\Modules\Defaults\Master\ReferensiData\KodePerkiraanKelompok\Model as ModelKelompok;
use App\Modules\Defaults\Master\ReferensiData\KodePerkiraanKelompok\ModelView as ViewModelKelompok;
use App\Modules\Defaults\Master\ReferensiData\KodePerkiraan\ModelView as ViewModelPerkiraan;
use App\Modules\Defaults\Master\ReferensiBarang\Kategori\Model as ModelKategori;
use App\Modules\Defaults\Master\ReferensiBarang\Bukuharian\Absen\Model as ModelAbsen;
use App\Modules\Defaults\Master\ReferensiBarang\Bukuharian\Lokasi\Model as ModelLokasi;
use App\Modules\Defaults\Master\ReferensiBarang\Satuan\Model as ModelSatuan;
use App\Modules\Defaults\Master\ReferensiData\SatuanKerja\Model as ModelSatuanKerja;
use App\Modules\Defaults\Master\ReferensiData\KodePerkiraanSatuanKerja\ModelView as ViewModelPerkiraanSatuanKerja;
use App\Modules\Defaults\Auth\Model\RolesModel as ModelRole;
use App\Modules\Defaults\ModelStandAlone\ModelSatker;
use App\Modules\Defaults\ProduksiDanDistribusi\RKA\Produksi\Model as ModelProduksi;
use App\Modules\Defaults\ProduksiDanDistribusi\RKA\Distribusi\Model as ModelDistribusi;
use App\Modules\Defaults\ProduksiDanDistribusi\RKA\AirTerjual\Model as ModelAirTerjual;
use App\Modules\Defaults\ProduksiDanDistribusi\RKA\AirBaku\Model as ModelAirBaku;
use App\Modules\Defaults\ProduksiDanDistribusi\RKAP\Produksi\Model as ModelProduksiPerubahan;
use App\Modules\Defaults\ProduksiDanDistribusi\RKAP\Distribusi\Model as ModelDistribusiPerubahan;
use App\Modules\Defaults\ProduksiDanDistribusi\RKAP\AirTerjual\Model as ModelAirTerjualPerubahan;
use App\Modules\Defaults\ProduksiDanDistribusi\RKAP\AirBaku\Model as ModelAirBakuPerubahan;
use App\Modules\Defaults\Ekuitas\RKA\MasterParameter\Model as ModelMasterParameter;
use App\Modules\Defaults\Ekuitas\RKAP\MasterParameter\Model as ModelRKAPMasterParameter;
use App\Modules\Defaults\ModelStandAlone\ModelAkunPerkiraan;
use App\Modules\Defaults\Master\ReferensiData\GolonganTarif\Model as ModelGolonganTarif;
use App\Modules\Defaults\Auth\Model\PdamModel as PdamModel;
use App\Modules\Defaults\ProyeksiArusKas\RKA\MasterParameter\Model as ModelArusKas;
use App\Modules\Defaults\ProyeksiArusKas\RKAP\MasterParameter\Model as ModelArusKasPerubahan;
use App\Modules\Defaults\ProyeksiNeraca\RKA\MasterParameter\Model as ModelNeraca;
use App\Modules\Defaults\ProyeksiNeraca\RKAP\MasterParameter\Model as ModelNeracaPerubahan;
use App\Modules\Defaults\Pendapatan\RKA\IndikatorAir\KelolaIndikatorAir\Model as ModelPendapatanAirNonAir;
use App\Modules\Defaults\Pendapatan\RKAP\IndikatorAir\KelolaIndikatorAir\Model as ModelPendapatanAirNonAirPerubahan;
use App\Modules\Defaults\Master\ReferensiData\PenandaTangan\LaporanModel as LaporanModel;


use Core\Facades\Request;

/**
 * @routeGroup('/referensi')
 */
class Controller extends BaseController
{
    /**
     * @routeGet("/getKelompok")
     * @routePost("/getKelompok")
     */
    public function getKelompokAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = ModelKelompokSatker::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "kelompok LIKE '%$nama%' AND pdam_id ='$pdam_id' AND is_aktif = '1'"
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getIndukKodePerkiraanGolongan")
     * @routePost("/getIndukKodePerkiraanGolongan")
     */
    public function getIndukKodePerkiraanGolonganAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$id = $this->request->get('id');
		$offset = ($page - 1) * 20;
		if($id){
			$data = ViewModelGolongan::find(
				array(
					'limit' => 21,
					'offset' => $offset,
					'conditions' => "nama LIKE '%$nama%' AND pdam_id ='$pdam_id' AND parent_id is null AND id != '$id'"
				)
			);
		}else{
			$data = ViewModelGolongan::find(
				array(
					'limit' => 21,
					'offset' => $offset,
					'conditions' => "nama LIKE '%$nama%' AND pdam_id ='$pdam_id' AND parent_id is null"
				)
			);
		}

		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getIndukKodePerkiraanKelompok")
     * @routePost("/getIndukKodePerkiraanKelompok")
     */
    public function getIndukKodePerkiraanKelompokAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = ViewModelKelompok::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "nama LIKE '%$nama%' AND pdam_id ='$pdam_id'"
			)
		);
		
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getKodePerkiraanKelompok")
     * @routePost("/getKodePerkiraanKelompok")
     */
    public function getKodePerkiraanKelompokAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = ViewModelKelompok::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "nama LIKE '%$nama%' AND pdam_id ='$pdam_id' AND parent_id is null"
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getIndukKodePerkiraan")
     * @routePost("/getIndukKodePerkiraan")
     */
    public function getIndukKodePerkiraanAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = ViewModelPerkiraan::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "nama LIKE '%$nama%' AND pdam_id =$pdam_id AND parent_id IS NULL "
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getKategori")
     * @routePost("/getKategori")
     */
    public function getKetegoriAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = ModelKategori::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "nama LIKE '%$nama%' AND pdam_id ='$pdam_id' "
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getSatuan")
     * @routePost("/getSatuan")
     */
    public function getSatuanAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = ModelSatuan::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "nama LIKE '%$nama%' AND pdam_id ='$pdam_id' "
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}
	/**
     * @routeGet("/getAbsen")
     * @routePost("/getAbsen")
     */
    public function getAbsenAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = ModelAbsen::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "status LIKE '%$nama%' AND pdam_id ='$pdam_id' "
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}
	/**
     * @routeGet("/getLokasi")
     * @routePost("/getLokasi")
     */
    public function getLokasiAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = ModelLokasi::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "lokasi LIKE '%$nama%' AND pdam_id ='$pdam_id' "
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getRole")
     * @routePost("/getRole")
     */
    public function getRoleAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = ModelRole::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "role LIKE '%$nama%' AND pdam_id ='$pdam_id' "
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getSatuanKerja")
     * @routePost("/getSatuanKerja")
     */
    public function getSatuanKerjaAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		// $data = ModelSatuanKerja::find(
		// 	array(
		// 		'limit' => 21,
		// 		'offset' => $offset,
		// 		'conditions' => "nama LIKE '%$nama%' AND pdam_id ='$pdam_id' AND id NOT IN (SELECT DISTINCT(parent_id) FROM master_satuan_kerja WHERE parent_id != 0)"
		// 	)
		// );
		$sql = "
		SELECT *
		FROM
		master_satuan_kerja 
		WHERE
			nama LIKE '%$nama%' 
			AND pdam_id = $pdam_id
			AND id NOT IN (
				SELECT DISTINCT(parent_id) 
				FROM master_satuan_kerja 
				WHERE parent_id != 0)
			LIMIT 20 OFFSET $offset
		";
		$bindParams = [
            'pdam_id' => $pdam_id,
            'nama' => $nama,
        ];
		$result = $this->db->fetchAll($sql);
		// return $this->response->setJsonContent($result);
		$has_more = count($result);
		$json_data = array(
			"data" => $result,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getSatuanKerjaNonSub")
     * @routePost("/getSatuanKerjaNonSub")
     */
    public function getSatuanKerjaNonSubAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = ModelSatuanKerja::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "nama LIKE '%$nama%' AND pdam_id ='$pdam_id' AND parent_id = 0 "
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getSatuanKerjaPendapatanAirNonAirExist")
     * @routePost("/getSatuanKerjaPendapatanAirNonAirExist")
     */
    public function getSatuanKerjaPendapatanAirNonAirExistAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$query = $this->modelsManager->createBuilder()
				->columns('DISTINCT s.*')
				->from(['s' => ModelSatuanKerja::class])
				->leftJoin(ModelPendapatanAirNonAir::class, 'p.kode_satker = s.kode', 'p')
				->where("s.pdam_id = '{$pdam_id}'")
				->andWhere('p.id IS NOT NULL')
				->limit(21)
				->offset($offset);
		$data = $query->getQuery()->execute();
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getSatuanKerjaPendapatanAirNonAirExistPerubahan")
     * @routePost("/getSatuanKerjaPendapatanAirNonAirExistPerubahan")
     */
    public function getSatuanKerjaPendapatanAirNonAirExistPerubahanAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$query = $this->modelsManager->createBuilder()
				->columns('DISTINCT s.*')
				->from(['s' => ModelSatuanKerja::class])
				->leftJoin(ModelPendapatanAirNonAirPerubahan::class, 'p.kode_satker = s.kode', 'p')
				->where("s.pdam_id = '{$pdam_id}'")
				->andWhere('p.id IS NOT NULL')
				->limit(21)
				->offset($offset);
		$data = $query->getQuery()->execute();
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getGolonganTarifPendapatanAirNonAirExist")
     * @routePost("/getGolonganTarifPendapatanAirNonAirExist")
     */
    public function getGolonganTarifPendapatanAirNonAirExistAction(){
        $kode_satker = Request::get('kode_satker');
        $pdam_id = $this->session->user['pdam_id'];
        $tahun = $this->session->user['tahun'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$query = $this->modelsManager->createBuilder()
				->columns('g.id, g.pdam_id, g.nama, i.kode_satker, i.id AS id_indikator ')
				->from(['g' => ModelGolonganTarif::class])
				->leftJoin(ModelPendapatanAirNonAir::class, 'i.golongan_tarif_id = g.id', 'i')
				->where("g.pdam_id = '{$pdam_id}'")
				->andWhere("(i.tahun = $tahun AND i.kode_satker = '{$kode_satker}' OR i.kode_satker IS NULL)")
				->groupBy('g.id')
				->limit(21)
				->offset($offset);
		$data = $query->getQuery()->execute();
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getGolonganTarifPendapatanAirNonAirExistPerubahan")
     * @routePost("/getGolonganTarifPendapatanAirNonAirExistPerubahan")
     */
    public function getGolonganTarifPendapatanAirNonAirExistPerubahanAction(){
        $kode_satker = Request::get('kode_satker');
        $pdam_id = $this->session->user['pdam_id'];
        $tahun = $this->session->user['tahun'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$query = $this->modelsManager->createBuilder()
				->columns('g.id, g.pdam_id, g.nama, i.kode_satker, i.id AS id_indikator ')
				->from(['g' => ModelGolonganTarif::class])
				->leftJoin(ModelPendapatanAirNonAirPerubahan::class, 'i.golongan_tarif_id = g.id', 'i')
				->where("g.pdam_id = '{$pdam_id}'")
				->andWhere("(i.tahun = $tahun AND i.kode_satker = '{$kode_satker}' OR i.kode_satker IS NULL)")
				->groupBy('g.id')
				->limit(21)
				->offset($offset);
		$data = $query->getQuery()->execute();
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getSatuanKerjaPendapatanAirNonAirNotExist")
     * @routePost("/getSatuanKerjaPendapatanAirNonAirNotExist")
     */
    public function getSatuanKerjaPendapatanAirNonAirNotExistAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$query = $this->modelsManager->createBuilder()
				->columns('DISTINCT s.*')
				->from(['s' => ModelSatuanKerja::class])
				->leftJoin(ModelPendapatanAirNonAir::class, 'p.kode_satker = s.kode', 'p')
				->where("s.pdam_id = '{$pdam_id}'")
				->andWhere('p.id IS NULL')
				->limit(21)
				->offset($offset);
		$data = $query->getQuery()->execute();
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getSatuanKerjaPendapatanAirNonAirNotExistPerubahan")
     * @routePost("/getSatuanKerjaPendapatanAirNonAirNotExistPerubahan")
     */
    public function getSatuanKerjaPendapatanAirNonAirNotExistPerubahanAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$query = $this->modelsManager->createBuilder()
				->columns('DISTINCT s.*')
				->from(['s' => ModelSatuanKerja::class])
				->leftJoin(ModelPendapatanAirNonAirPerubahan::class, 'p.kode_satker = s.kode', 'p')
				->where("s.pdam_id = '{$pdam_id}'")
				->andWhere('p.id IS NULL')
				->limit(21)
				->offset($offset);
		$data = $query->getQuery()->execute();
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getSatuanKerjaProdis")
     * @routePost("/getSatuanKerjaProdis")
     */
    public function getSatuanKerjaProdisAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$query = $this->modelsManager->createBuilder()
				->from(['s' => ModelSatuanKerja::class])
				->leftJoin(ModelProduksi::class, 'p.satuan_kerja_id = s.kode', 'p')
				->where("s.pdam_id = '{$pdam_id}'")
				->andWhere('p.satuan_kerja_id IS NULL')
				->andWhere("s.nama LIKE '%$nama%'")
				->limit(21)
				->offset($offset);
		$data = $query->getQuery()->execute();
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getSatuanKerjaProdisDistribusi")
     * @routePost("/getSatuanKerjaProdisDistribusi")
     */
    public function getSatuanKerjaProdisDistribusiAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$query = $this->modelsManager->createBuilder()
				->from(['s' => ModelSatuanKerja::class])
				->leftJoin(ModelDistribusi::class, 'p.satuan_kerja_id = s.kode', 'p')
				->where("s.pdam_id = '{$pdam_id}'")
				->andWhere('p.satuan_kerja_id IS NULL')
				->andWhere("s.nama LIKE '%$nama%'")
				->limit(21)
				->offset($offset);
		$data = $query->getQuery()->execute();
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getSatuanKerjaProdisAirTerjual")
     * @routePost("/getSatuanKerjaProdisAirTerjual")
     */
    public function getSatuanKerjaProdisAirTerjualAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$query = $this->modelsManager->createBuilder()
				->from(['s' => ModelSatuanKerja::class])
				->leftJoin(ModelAirTerjual::class, 'p.satuan_kerja_id = s.kode', 'p')
				->where("s.pdam_id = '{$pdam_id}'")
				->andWhere('p.satuan_kerja_id IS NULL')
				->andWhere("s.nama LIKE '%$nama%'")
				->limit(21)
				->offset($offset);
		$data = $query->getQuery()->execute();
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getSatuanKerjaProdisAirBaku")
     * @routePost("/getSatuanKerjaProdisAirBaku")
     */
    public function getSatuanKerjaProdisAirBakuAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$query = $this->modelsManager->createBuilder()
				->from(['s' => ModelSatuanKerja::class])
				->leftJoin(ModelAirBaku::class, 'p.satuan_kerja_id = s.kode', 'p')
				->where("s.pdam_id = '{$pdam_id}'")
				->andWhere('p.satuan_kerja_id IS NULL')
				->andWhere("s.nama LIKE '%$nama%'")
				->limit(21)
				->offset($offset);
		$data = $query->getQuery()->execute();
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getSatuanKerjaProdisProduksiPerubahan")
     * @routePost("/getSatuanKerjaProdisProduksiPerubahan")
     */
    public function getSatuanKerjaProdisProduksiPerubahanAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$query = $this->modelsManager->createBuilder()
				->from(['s' => ModelSatuanKerja::class])
				->leftJoin(ModelProduksiPerubahan::class, 'p.satuan_kerja_id = s.kode', 'p')
				->where("s.pdam_id = '{$pdam_id}'")
				->andWhere('p.satuan_kerja_id IS NULL')
				->andWhere("s.nama LIKE '%$nama%'")
				->limit(21)
				->offset($offset);
		$data = $query->getQuery()->execute();
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getSatuanKerjaProdisDistribusiPerubahan")
     * @routePost("/getSatuanKerjaProdisDistribusiPerubahan")
     */
    public function getSatuanKerjaProdisDistribusiPerubahanAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$query = $this->modelsManager->createBuilder()
				->from(['s' => ModelSatuanKerja::class])
				->leftJoin(ModelDistribusiPerubahan::class, 'p.satuan_kerja_id = s.kode', 'p')
				->where("s.pdam_id = '{$pdam_id}'")
				->andWhere('p.satuan_kerja_id IS NULL')
				->andWhere("s.nama LIKE '%$nama%'")
				->limit(21)
				->offset($offset);
		$data = $query->getQuery()->execute();
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getSatuanKerjaProdisAirTerjualPerubahan")
     * @routePost("/getSatuanKerjaProdisAirTerjualPerubahan")
     */
    public function getSatuanKerjaProdisAirTerjualPerubahanAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$query = $this->modelsManager->createBuilder()
				->from(['s' => ModelSatuanKerja::class])
				->leftJoin(ModelAirTerjualPerubahan::class, 'p.satuan_kerja_id = s.kode', 'p')
				->where("s.pdam_id = '{$pdam_id}'")
				->andWhere('p.satuan_kerja_id IS NULL')
				->andWhere("s.nama LIKE '%$nama%'")
				->limit(21)
				->offset($offset);
		$data = $query->getQuery()->execute();
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getSatuanKerjaProdisAirBakuPerubahan")
     * @routePost("/getSatuanKerjaProdisAirBakuPerubahan")
     */
    public function getSatuanKerjaProdisAirBakuPerubahanAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$query = $this->modelsManager->createBuilder()
				->from(['s' => ModelSatuanKerja::class])
				->leftJoin(ModelAirBakuPerubahan::class, 'p.satuan_kerja_id = s.kode', 'p')
				->where("s.pdam_id = '{$pdam_id}'")
				->andWhere('p.satuan_kerja_id IS NULL')
				->andWhere("s.nama LIKE '%$nama%'")
				->limit(21)
				->offset($offset);
		$data = $query->getQuery()->execute();
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getKodePerkiraanSatuanKerja")
     * @routePost("/getKodePerkiraanSatuanKerja")
     */
    public function getKodePerkiraanSatuanKerjaAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = ViewModelPerkiraanSatuanKerja::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "nama LIKE '%$nama%' AND pdam_id ='$pdam_id' "
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getSatker")
     * @routePost("/getSatker")
     */
    public function getSatkerAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		// $data = ModelSatker::find(
		// 	array(
		// 		'limit' => 21,
		// 		'offset' => $offset,
		// 		'conditions' => "nama LIKE '%$nama%' AND pdam_id ='$pdam_id' "
		// 	)
		// );

		$sql = "
		SELECT *
		FROM
		master_satuan_kerja 
		WHERE
			nama LIKE '%$nama%' 
			AND pdam_id = $pdam_id
			AND id NOT IN (
				SELECT DISTINCT(parent_id) 
				FROM master_satuan_kerja 
				WHERE parent_id != 0)
				LIMIT 20 OFFSET $offset
		";
		$bindParams = [
            'pdam_id' => $pdam_id,
            'nama' => $nama,
        ];
		$result = $this->db->fetchAll($sql);
		// return $this->response->setJsonContent($result);
		// var_dump($result);exit;
		$has_more = count($result);
		$json_data = array(
			"data" => $result,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getMasterParameter")
     * @routePost("/getMasterParameter")
     */
    public function getMasterParameterAction(){
        $pdam_id = $this->session->user['pdam_id'];
        $tahun = $this->session->user['tahun'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = ModelMasterParameter::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "uraian LIKE '%$nama%' AND pdam_id ='$pdam_id' AND tahun ='$tahun' AND parent_id =0 "
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getMasterParameterRkap")
     * @routePost("/getMasterParameterRkap")
     */
    public function getMasterParameterRkapAction(){
        $pdam_id = $this->session->user['pdam_id'];
        $tahun = $this->session->user['tahun'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = ModelArusKas::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "paramet LIKE '%$nama%' AND pdam_id ='$pdam_id' AND tahun ='$tahun' AND parent_id =0 "
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getMasterParameterArusKas")
     * @routePost("/getMasterParameterArusKas")
     */
    public function getMasterParameterArusKasAction(){
        $pdam_id = $this->session->user['pdam_id'];
        $tahun = $this->session->user['tahun'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = ModelArusKas::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "parameter LIKE '%$nama%' AND pdam_id =$pdam_id AND tahun =$tahun AND parent = 0 "
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getMasterParameterArusKasPerubahan")
     * @routePost("/getMasterParameterArusKasPerubahan")
     */
    public function getMasterParameterArusKasPerubahanAction(){
        $pdam_id = $this->session->user['pdam_id'];
        $tahun = $this->session->user['tahun'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = ModelArusKasPerubahan::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "parameter LIKE '%$nama%' AND pdam_id =$pdam_id AND tahun =$tahun AND parent = 0 "
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getMasterParameterNeraca")
     * @routePost("/getMasterParameterNeraca")
     */
    public function getMasterParameterNeracaAction(){
        $pdam_id = $this->session->user['pdam_id'];
        $tahun = $this->session->user['tahun'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = ModelNeraca::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "parameter LIKE '%$nama%' AND pdam_id =$pdam_id AND tahun =$tahun AND parent = 0 "
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getMasterParameterNeracaPerubahan")
     * @routePost("/getMasterParameterNeracaPerubahan")
     */
    public function getMasterParameterNeracaPerubahanAction(){
        $pdam_id = $this->session->user['pdam_id'];
        $tahun = $this->session->user['tahun'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = ModelNeracaPerubahan::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "parameter LIKE '%$nama%' AND pdam_id =$pdam_id AND tahun =$tahun AND parent = 0 "
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

    /**
     * @routeGet("/getAkunPerkiraanPrefix")
     * @routePost("/getAkunPerkiraanPrefix")
     */
	public function getAkunPerkiraanPrefixAction()
	{
		$pdam_id = $this->session->user['pdam_id'];
		$satker = Request::get('dept_id');
		$prefix = Request::get('prefix');
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		
		if($satker == ""){
			$data = ModelAkunPerkiraan::find(
				array(
					'limit' => 20,
					'offset' => $offset,
					'conditions' => "(nama LIKE '%$nama%' OR kode LIKE '%$nama%') AND pdam_id ='$pdam_id' AND is_aktif=1 AND kode LIKE '".$prefix."%'"
				)
			);
		}
		else{
			$data = ModelAkunPerkiraan::find(
				array(
					'limit' => 21,
					'offset' => $offset,
					'conditions' => "(nama LIKE '%$nama%' OR kode LIKE '%$nama%') AND id_satuan_kerja = '$satker' AND pdam_id ='$pdam_id' AND is_aktif=1 AND kode LIKE '".$prefix."%'"
				)
			);
		}
		
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

    /**
     * @routeGet("/getAkunPerkiraanNew")
     * @routePost("/getAkunPerkiraanNew")
     */
	public function getAkunPerkiraanNewAction()
	{
		$pdam_id = $this->session->user['pdam_id'];
		$satker = Request::get('dept_id');
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		// if($satker == ""){
		// 	$data = ModelAkunPerkiraan::find(
		// 		array(
		// 			'limit' => 21,
		// 			'offset' => $offset,
		// 			'conditions' => "(nama LIKE '%$nama%' OR kode LIKE '%$nama%') AND pdam_id ='$pdam_id' AND is_aktif=1 AND is_hapus=0"
		// 		)
		// 	);
		// }
		// else{
		// 	$data = ModelAkunPerkiraan::find(
		// 		array(
		// 			'limit' => 21,
		// 			'offset' => $offset,
		// 			'conditions' => "(nama LIKE '%$nama%' OR kode LIKE '%$nama%') AND id_satuan_kerja = '$satker' AND pdam_id ='$pdam_id' AND is_aktif=1 AND is_hapus=0"
		// 		)
		// 	);
		// }

		if($satker == ""){
			$data = ModelAkunPerkiraan::find(
				array(
					'limit' => 21,
					'offset' => $offset,
					'conditions' => "(nama LIKE '%$nama%' OR kode LIKE '%$nama%') AND pdam_id ='$pdam_id' AND is_aktif=1"
				)
			);
		}
		else{
			$data = ModelAkunPerkiraan::find(
				array(
					'limit' => 21,
					'offset' => $offset,
					'conditions' => "(nama LIKE '%$nama%' OR kode LIKE '%$nama%') AND id_satuan_kerja = '$satker' AND pdam_id ='$pdam_id' AND is_aktif=1"
				)
			);
		}

		$data_array = $data->toArray();
		return $this->response->setJsonContent($data_array);
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/nomorUsulan")
     * @routePost("/nomorUsulan")
     */
	public function nomorUsulanAction()
	{
		header('Content-Type: application/json; charset=utf-8'); 
		$bagID = Request::get('bag_id');
		// var_dump($bagID);exit;
		if(!$bagID) $bagID = 0;
		$sql = "CALL generate_number(" . date('Y') . ", 'biaya', $bagID)";
		$data = $this->db->fetchAll($sql);

		echo json_encode($data[0]);
	}

	/**
     * @routeGet("/getGolonganTarif")
     * @routePost("/getGolonganTarif")
     */
	public function getGolonganTarifAction()
	{
		$pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		
		$offset = ($page - 1) * 20;
		$data = ModelGolonganTarif::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "(nama LIKE '%$nama%' OR kode LIKE '%$nama%')  AND pdam_id ='$pdam_id' "
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

	/**
     * @routeGet("/getPdam")
     * @routePost("/getPdam")
     */
    public function getPdamAction(){
		$nama = $this->request->get('q');
		$page = $this->request->get('page');	
		$offset = ($page - 1) * 20;
		$data = PdamModel::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "nama_pdam LIKE '%$nama%'  "
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}
	
	/**
     * @routeGet("/getEkuitasParameterRkap")
     * @routePost("/getEkuitasParameterRkap")
     */
    public function getEkuitasParameterRkapAction(){
        $pdam_id = $this->session->user['pdam_id'];
        $tahun = $this->session->user['tahun'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = ModelRKAPMasterParameter::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "uraian LIKE '%$nama%' AND pdam_id ='$pdam_id' AND tahun ='$tahun' AND parent_id =0 "
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}

		/**
     * @routeGet("/getDataLaporan")
     * @routePost("/getDataLaporan")
     */
    public function getDataLaporanAction(){
        $pdam_id = $this->session->user['pdam_id'];
		$nama = $this->request->get('q');
		$page = $this->request->get('page');
		$offset = ($page - 1) * 20;
		$data = LaporanModel::find(
			array(
				'limit' => 21,
				'offset' => $offset,
				'conditions' => "uraian LIKE '%$nama%' "
			)
		);
		$data_array = $data->toArray();
		$has_more = count($data_array);
		$json_data = array(
			"data" => $data_array,
			"has_more" => $has_more,
		);
		echo json_encode($json_data);
	}
}