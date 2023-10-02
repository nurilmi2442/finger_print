<?php
namespace App\Repositories\Eloquent;
use App\Repositories\Eloquent\GenericEloquentRepo;
use App\GlobalClass\KonfigurasiDokumen;
use App\Presentation\ResponseCreator;
use Carbon\Carbon;
use App\Models\Kategori;
use App\GlobalClass\ImsToken;
use App\Adapter\ImsAdapter;
use App\Models\LpbgHdr;
use App\Models\SttpHdr;
use App\Models\SttpDtl;
use App\Models\LpbgDtl;
use App\Models\Material;

class TransaksiRepo extends GenericEloquentRepo{

	public function getSoContract($params){
		try{
			$imsAdapter = new ImsAdapter();
			$data = $imsAdapter->getSoContract(json_encode($params));

			$data = json_decode($data);
			$response = new ResponseCreator(200, 'Berhasil di dapat', $data, []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function getSttp($params = []){
		try{

			$where = ' 1=1 ';
			$p=[];
			if($params['search']){
				$where = ' (sttphdr.docno like ? or gedung like ? or lokasi like ? or no_sttp like ?)';
				$p = ['%'.$params['search'].'%','%'.$params['search'].'%','%'.$params['search'].'%','%'.$params['search'].'%'];
			}


			$data = SttpHdr::leftJoin('lokasi','lokasi.id','=','sttphdr.lokasi_id')
			->leftJoin('gedung','gedung.id','=','sttphdr.gedung_id')
			->whereNull('sttphdr.deleted_at')
			->whereRaw($where, $p)
			->selectRaw('sttphdr.*, lokasi, gedung, date(tanggal) tanggal_')
			->paginate(10);
			$response = new ResponseCreator(200, 'Berhasil di dapat', $data, []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function getSttpById($id){
		try{
			$data = SttpHdr::whereNull('deleted_at')->selectRaw('*, date(tanggal) tanggal_')->where('id','=',$id)->first();
			$response = new ResponseCreator(200, 'Berhasil', $data, []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function SimpanSttp($params){
		try{
			if($params['id']){
				$res = SttpHdr::where('id','=',$params['id'])->first();
				unset($params['id']);
				if($params['tanggal']){
					$params['tanggal'] = date('Y-m-d', strtotime($params['tanggal']));
				}
				$res->update($params);
			}
			else{
				unset($params['id']);
				$sttp= SttpHdr::whereNull('deleted_at')->where('tahun','=',date('Y'))->max('nourut');
				$no = sprintf('%04d',1);
				if($sttp){
					$no = sprintf('%04d',$sttp + 1);
				}
				$nosttp = $no.date('dmY');
				$params['no_sttp'] = $nosttp;
				$params['nourut'] = $no;
				$params['tahun'] = date('Y');
				$res = SttpHdr::create($params);
			}
			
			$response = new ResponseCreator(200, 'Berhasil di simpan', [], []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function SimpanMaterial($params){
		try{
			if($params['id']){
				$res = Material::find($params['id']);
				unset($params['id']);
				$res->update($params);
			}
			else{
				unset($params['id']);
				$material = Material::create($params);
				
			}
			
			$response = new ResponseCreator(200, 'Berhasil di simpan', [], []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function SimpanMaterialLpbg($params){
		try{

			$lpbg = LpbgDtl::create($params);
			$response = new ResponseCreator(200, 'Berhasil di simpan', [], []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}


	public function SimpanMaterialSttp($params){
		try{

			$sttp = SttpDtl::create($params);
			$response = new ResponseCreator(200, 'Berhasil di simpan', [], []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function SimpanLpbg($params){
		try{
			$data = [];
			if($params['id']){
				$res = LpbgHdr::find($params['id']);
				unset($params['id']);
				if($params['tanggal']){
					$params['tanggal'] = date('Y-m-d', strtotime($params['tanggal']));
				}
				$res->update($params);
			}
			else{
				unset($params['id']);
				if($params['tanggal']){
					$params['tanggal'] = date('Y-m-d', strtotime($params['tanggal']));
				}
				$lpbg = LpbgHdr::create($params);
				$data = ['id'=>$lpbg->id];
				
			}
			
			$response = new ResponseCreator(200, 'Berhasil di simpan', $data , []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function getMaterial($params = []){
		try{

			$data = new Material();

			if(!empty($params['search'])){
				$data = $data->whereRaw('kode_material like ? or deskripsi like ?',['%'.$params['search'].'%','%'.$params['search'].'%']);
			}

			if(!empty($params['all'])){

				$data = $data->selectRaw('*, concat(kode_material, " - ", deskripsi) name')->whereNull('deleted_at')->get();
			}
			else{
				$data = $data->whereNull('deleted_at')->paginate(15);
			}
			$response = new ResponseCreator(200, 'Berhasil', $data, []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function getMaterialLpbg($params = []){
		try{

			$data = new LpbgDtl();


			$data = $data->whereNull('lpbgdtl.deleted_at')
			->selectRaw('lpbgdtl.*, kode_material, deskripsi, qty, satuan')
			->leftJoin('material','material.id','=','LpbgDtl.material_id');
			

			if(empty($params['is_search'])){
				$data = $data->where('lpbghdr_id','=',$params['lpbghdr_id'])
				->paginate(15);
			}
			else{
				$search = '%'.$params['search'].'%';
				if($params['search']){
					$data = $data->whereRaw('lpbghdr.no_lpbg like ? or material.kode_material like ? or material.deskripsi like ?',[
						$search,$search,$search
					]);
				}
				$data = $data->leftJoin('lpbghdr','lpbghdr.id','=','lpbgdtl.lpbghdr_id')
				->where('lpbghdr.kode_proyek','=',$params['kode_proyek'])
				->selectRaw(' concat(kode_material, " - ", deskripsi, " (", lpbghdr.no_lpbg, ") ") as name ')
				->get();
			}
			$response = new ResponseCreator(200, 'Berhasil', $data, []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function getMaterialSttp($params = []){
		try{

			$data = new SttpDtl();

			$data = $data->whereNull('sttpdtl.deleted_at')
			->selectRaw('sttpdtl.*, kode_material, deskripsi, sttpdtl.qty, material.satuan, lpbghdr.no_lpbg')
			->leftJoin('lpbgdtl','lpbgdtl.id','=','sttpdtl.lpbgdtl_id')
			->leftJoin('lpbghdr','lpbghdr.id','=','lpbgdtl.lpbghdr_id')
			->leftJoin('material','material.id','=','lpbgdtl.material_id')
			->where('sttphdr_id','=',$params['sttphdr_id'])
			->paginate(15);
			$response = new ResponseCreator(200, 'Berhasil', $data, []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function getLpbgById($id){
		try{
			$data = LpbgHdr::whereNull('deleted_at')->selectRaw('*, date(tanggal) tanggal_')->where('id','=',$id)->first();
			$response = new ResponseCreator(200, 'Berhasil', $data, []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function getLpbg($params){
		try{
			$data = new LpbgHdr();
			if($params['search']){
				$data = $data->whereRaw('no_lpbg like ? or kode_proyek like ?', ['%'.$params['search'].'%','%'.$params['search'].'%']);
			}
			$data = $data->whereNull('deleted_at')->selectRaw('*, date(tanggal) tanggal_')->paginate(15);
			$response = new ResponseCreator(200, 'Berhasil', $data, []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function DeleteSttp($id){
		try{
			$res = SttpHdr::find($id);
			$res->deleted_at = Carbon::now();
			$res->save();
			$response = new ResponseCreator(200, 'Berhasil di simpan', [], []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function HapusMaterial($id){
		try{
			$res = Material::find($id);
			$res->deleted_at = Carbon::now();
			$res->save();
			$response = new ResponseCreator(200, 'Berhasil di simpan', [], []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function HapusMaterialLpbg($id){
		try{
			$res = LpbgDtl::find($id);
			$res->deleted_at = Carbon::now();
			$res->save();
			$response = new ResponseCreator(200, 'Berhasil di simpan', [], []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function HapusMaterialSttp($id){
		try{
			$res = SttpDtl::find($id);
			$res->deleted_at = Carbon::now();
			$res->save();
			$response = new ResponseCreator(200, 'Berhasil di simpan', [], []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function HapusLpbg($id){
		try{
			$res = LpbgHdr::find($id);
			$res->deleted_at = Carbon::now();
			$res->save();
			$response = new ResponseCreator(200, 'Berhasil di simpan', [], []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}
}
?>