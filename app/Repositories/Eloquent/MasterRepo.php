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
use App\GlobalClass\ImsToken;
use App\Adapter\ImsAdapter;
use Illuminate\Support\Facades\Hash;

class MasterRepo extends GenericEloquentRepo{


	public function getDataProyek($params = []){
		try{
			$data = Proyek::whereNull('deleted_at')->paginate(5);

			if(!empty($params['all'])){
				$data = Proyek::selectRaw('*, concat(kode_proyek, " - ", nama_proyek) as name')->whereNull('deleted_at')->get();
			}
			$response = new ResponseCreator(200,'Berhasil di dapat',$data,[]);
		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function getDataLokasi($params = []){
		try{
			$data = Lokasi::whereNull('deleted_at')->paginate(5);

			if(!empty($params['all'])){
				$data = Lokasi::whereNull('deleted_at')->get();
			}
			$response = new ResponseCreator(200,'Berhasil di dapat',$data,[]);
		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function getDataUser($params = []){
		try{
			$user = new User();
			$query = $user
			->leftJoin('roles','roles.id','=','users.role')
			->whereNull('deleted_at')
			->selectRaw('users.*, roles.role name_role');
			$data = $query->paginate(10);

			if(!empty($params['all'])){
				$data = $query->get();
			}
			$response = new ResponseCreator(200,'Berhasil di dapat',$data,[]);
		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function getDataGedung($params = []){
		try{
			$data = Gedung::whereNull('deleted_at')->paginate(5);
			if(!empty($params['all'])){
				$data = Gedung::whereNull('deleted_at')->get();
			}
			$response = new ResponseCreator(200,'Berhasil di dapat',$data,[]);
		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function simpanLokasi($params){
		try{

			if($params->id){
				$data = Lokasi::find($params->id);
				$data->lokasi = $params->lokasi;
				$data->save();
			}
			else{
				$data = new Lokasi();
				$data->lokasi = $params->lokasi;
				$data->save();
			}
			
			$response = new ResponseCreator(200, 'Berhasil di simpan', [],[]);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function simpanProyek($params){
		try{

			if($params['id']){
				$data = Proyek::find($params['id']);
				unset($params['id']);
				$data->update($params);
			}
			else{
				unset($params['id']);
				$data = Proyek::create($params);
			}
			
			$response = new ResponseCreator(200, 'Berhasil di simpan', [],[]);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function simpanUser($params){
		try{

			if($params->id){
				$data = User::find($params->id);
				$data->name = $params->name;
				$data->nip = $params->nip;
				$data->role = $params->role;
				if($params->password){
					$data->password = Hash::make($params->password);
				}
				$data->save();
			}
			else{
				$data = new User();
				$data->name = $params->name;
				$data->nip = $params->nip;
				$data->role = $params->role;
				$data->password = Hash::make($params->password);
				$data->save();
			}
			
			
			$response = new ResponseCreator(200, 'Berhasil di simpan', [],[]);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function simpanGedung($params){
		try{

			if($params->id){
				$data = Gedung::find($params->id);
				$data->gedung = $params->gedung;
				$data->save();
			}
			else{
				$data = new Gedung();
				$data->gedung = $params->gedung;
				$data->save();
			}
			
			$response = new ResponseCreator(200, 'Berhasil di simpan', [],[]);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

		public function hapusLokasi($id){
		try{

			$lokasi = Lokasi::find($id);
			$lokasi->deleted_at = Carbon::now();
			$lokasi->save();
			$response = new ResponseCreator(200, 'Berhasil di hapus', [],[]);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function hapusUser($id){
		try{

			$lokasi = User::find($id);
			$lokasi->deleted_at = Carbon::now();
			$lokasi->save();
			$response = new ResponseCreator(200, 'Berhasil di hapus', [],[]);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function hapusGedung($id){


			$lokasi = Gedung::find($id);
			$lokasi->deleted_at = Carbon::now();
			$lokasi->save();
			$response = new ResponseCreator(200, 'Berhasil di hapus', [],[]);

		
		return $response->getResponse();
	}
}
?>