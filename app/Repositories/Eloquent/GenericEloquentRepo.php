<?php
namespace App\Repositories\Eloquent;

class GenericEloquentRepo{

	 protected function generateRandomString($length = 20)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    protected function checkImageExist($path = null,$direktori=null, $file=null){
        if(file_exists($direktori.$file) && !empty($file)){
            return $path.$file;
        }
        else{
            return $path.'no_image.jpg';
        }
    }

    protected function uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    protected function _uploadImage($direktori, $file){
         if(!empty($file)){
             /**
                create folder images slider
             */
            if (!file_exists($direktori)) {
                mkdir($direktori, 0777, true);
            }
                $folder = $direktori;
                $ekstensi = $file->getClientOriginalExtension();
                if ($ekstensi !== 'png' && $ekstensi !== 'jpg' && $ekstensi !== 'jpeg' && $ekstensi !== 'PNG' && $ekstensi !== 'JPG' && $ekstensi !== 'JPEG') {
                
                    $response = array('status'=>false, 'message'=>'File yang anda masukkan tidak valid');
                    return $response;
                }

                $namaFileAsli = $file->getClientOriginalName();
                $file_generated = date('YmdHis') . '_' . $this->generateRandomString();
                $file_generated = str_replace(' ', '_', $file_generated);
                $file_generated = $file_generated . "." . $ekstensi;
                $file->move($folder, $file_generated);

                $response = array('status'=>true, 'file_asli'=> $namaFileAsli, 'file_generated'=>$file_generated);
                return $response;

            }
        
        }
}
?>