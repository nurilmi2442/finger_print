<?php
namespace App\Globalclass;

class KonfigurasiEndPoinApi{

	public function __construct(){
		$this->url = env('APP_URL_IMS');
	}

	 public function ImsApi()
    {
        return $this->url;
    }
}

?>