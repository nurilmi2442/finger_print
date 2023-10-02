<?php 
namespace App\GlobalClass;
use Illuminate\Support\Facades\Crypt;

class Imstoken {
	public static function generateToken($params){
		$result = Crypt::encrypt($params);
        return $result;
	}

	public static function claimToken($params){
		$result = Crypt::decrypt($params);
        return $result;
	}
}

?>