<?php 
namespace App\Repositories\Eloquent;
use App\Repositories\Eloquent\GenericEloquentRepo;
use App\Presentation\ResponseCreator;
use App\Models\UserLogin;
use DateTime;
use App\GlobalClass\ImsToken;

class UserLoginRepo extends GenericEloquentRepo {

	private $userlogin;

	public function __construct(UserLogin $userlogin){
		$this->model = $userlogin;
	}

	public function aksiAmbilUser(){
		try{
			$user = $this->model->with('Agama')->with(['riwayat_pendidikan' => function($query){
				$query->select('jenjang','user_id')
				->where('id','=',4);
			}])->get();
			$response = new ResponseCreator(200, 'Data ditemukan', $user, []);
		}
		catch(\Exception $e){
			$errors[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $errors);
		}

		return $response->getResponse();
	}


	public function aksiTambahUser($params){
		try{
			$user = $this->model->create($params);

			// $user = $this->model;
			// $user->nama = $params['nama'];
			// $user->alamat = $params['alamat'];
			// $user->save();

			$response = new ResponseCreator(200, 'Data berhasil di tambah', [], []);
		}
		catch(\Exception $e){
			$errors[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $errors);
		}

		return $response->getResponse();
	}

	public function aksiUbahUser($params){
		try{
			$user = $this->model->find($params['id']);

			// unset($params['id']);
			// $user->update($params);


			$user->nama = $params['nama'];
			$user->alamat = $params['alamat'];

			$user->save();


			$response = new ResponseCreator(200, 'Data berhasil di ubah', [], []);
		}
		catch(\Exception $e){
			$errors[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $errors);
		}

		return $response->getResponse();
	}

	public function aksiHapusUser($params){
		try{
			$user = $this->model->find($params['id']);

			$user->delete();


			$response = new ResponseCreator(200, 'Data berhasil di hapus', [], []);
		}
		catch(\Exception $e){
			$errors[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $errors);
		}

		return $response->getResponse();
	}

	public function doLogin($params){
		try{
			$username = $params->username;
			$password = $params->password;


			$salt_password  = config('salt_password.salt_password');
		    $password_encry = md5( md5( $password. $salt_password ) ) . md5( $password . $salt_password );

		    $res = $this->model->where('username','=',$username)->where('password','=',$password_encry)->first();


		    if(empty($res)){
		    	$response = new ResponseCreator(401, 'Pengguna tidak di temukan', [],[]);
		    	return $response->getResponse();
		    }

		    $start = new DateTime(date('Y-m-d H:i:s'));
            $end = clone $start;

            // set expired time for 1 days
            $end = $end->modify('+1 days');
            $expired_time = $end->format('Y-m-d H:i:s');

            $pengguna = array(
            	'username' => $res->username,
            	'id'=>$res->id,
            	'expired_time'=> $expired_time
            );

            // generate token
      		$token = ImsToken::generateToken($pengguna);

      		$data = array('token'=>$token,'id'=>$pengguna['id'],'username'=>$pengguna['username']);
      		$response = new ResponseCreator(200, 'Berhasil Mendapatkan data', $data, []);
     
		}
		catch(\Exception $e){
			$errors[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $errors);
		}
		return $response->getResponse();
	}
}

?>