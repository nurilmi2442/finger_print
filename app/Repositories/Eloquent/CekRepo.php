<?php
namespace App\Repositories\Eloquent;
use App\Repositories\Eloquent\GenericEloquentRepo;
use App\GlobalClass\KonfigurasiDokumen;
use App\Presentation\ResponseCreator;
use Carbon\Carbon;
use App\Models\Lokasi;
use App\Models\Gedung;
use App\Models\User;
use App\Models\Proyek;
use App\Models\CekLpbg;
use App\Models\CekSttp;
use Illuminate\Support\Facades\DB;

class CekRepo extends GenericEloquentRepo{


	public function CekLpbg($params = []){
		try{
			$data = new CekLpbg();

			if($params['search']){
				$search = '%'.$params['search'].'%';
				$data = $data->whereRaw('no_lpbg like ? or kode_material like ? or kode_proyek like ? 
or deskripsi like ? ',[$search,$search,$search,$search]);
			}
			$data = $data->paginate(15);
			$response = new ResponseCreator(200, 'Berhasil di dapat', $data, []);
		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function CekSttp($params = []){
		try{
			$data = new CekSttp();

			if($params['search']){
				$search = '%'.$params['search'].'%';
				$data = $data->whereRaw('no_lpbg like ? or kode_material like ? or kode_proyek like ? 
or deskripsi like ? or no_sttp like ? ',[$search,$search,$search,$search,$search]);
			}
			

			if($params['start'] && $params['end']){
				$data = $data->whereRaw("
					tanggal between ? and ?
				",[$params['start'],$params['end']]);
			}
			$data = $data->paginate(15);
			
			$response = new ResponseCreator(200, 'Berhasil di dapat', $data, []);
		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

}
?>