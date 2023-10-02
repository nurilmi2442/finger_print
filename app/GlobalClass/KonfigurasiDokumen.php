<?php 
namespace App\GlobalClass;

class KonfigurasiDokumen
{

	public function __construct(){
		$this->globalEndPoint = env('APP_URL');
	}
	public function ambilDirektoriUploads(){
		 return 'public/uploads/';
	}

	public function ambilPathUploads(){
		 return $this->globalEndPoint.'/public/uploads/';
	}
}
?>