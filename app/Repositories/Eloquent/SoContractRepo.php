<?php
namespace App\Repositories\Eloquent;
use App\Repositories\Eloquent\GenericEloquentRepo;
use App\GlobalClass\KonfigurasiDokumen;
use App\Presentation\ResponseCreator;
use Carbon\Carbon;
use App\Models\Kategori;
use App\GlobalClass\ImsToken;
use App\Adapter\ImsAdapter;

class SoContractRepo extends GenericEloquentRepo{

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
}
?>