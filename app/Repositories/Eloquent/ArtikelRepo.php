<?php
namespace App\Repositories\Eloquent;
use App\Repositories\Eloquent\GenericEloquentRepo;
use App\GlobalClass\KonfigurasiDokumen;
use App\Presentation\ResponseCreator;
use Carbon\Carbon;
use App\Models\Pencarian;
use App\Models\Kategori;
use App\Models\Keyword;
use App\GlobalClass\ImsToken;
use App\Adapter\PythonAdapter;
use App\Models\Dokumen;
class ArtikelRepo extends GenericEloquentRepo{


	public function cariArtikel($params){
		try{
			$pythonAdapter = new PythonAdapter();
			$start = $params->start ? $params->start : 0;
			$datakeyword = $params->data;
			$data = [];
			$keyword = '';
			if($datakeyword){
				$data = array_column($datakeyword, 'keyword');
				foreach ($data as $key => $value) {
					if($key==0){
						$keyword = ''.$value.'';
					}
					else{
						$keyword.=' or '.$value.'';
					}
				}

				$keyword.=' filetype pdf'; 

			}

			$keyword = urlencode($keyword);
			$params = array(
				'keyword'=>$keyword,
				'start'=>$start
			);
			$data = $pythonAdapter->cariArtikel($params);

			$res = array('data'=>json_decode($data),'start'=>$start);
			$response = new ResponseCreator(200,'Berhasil di dapat',$res,[]);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function cariArtikelGoogle($params){
		try{
			$pythonAdapter = new PythonAdapter();
			$start = $params->start ? $params->start : 0;
			$datakeyword = $params->data;
			$data = [];
			$keyword = '';
			if($datakeyword){
				$data = array_column($datakeyword, 'keyword');
				foreach ($data as $key => $value) {
					if($key==0){
						$keyword = ''.$value.'';
					}
					else{
						$keyword.=' or '.$value.'';
					}
				}

				$keyword.=' filetype pdf'; 

			}

			$keyword = urlencode($keyword);
			$params = array(
				'keyword'=>$keyword,
				'start'=>$start
			);
			$data = $pythonAdapter->cariArtikelGoogle($params);

			$res = array('data'=>json_decode($data),'start'=>$start);
			$response = new ResponseCreator(200,'Berhasil di dapat',$res,[]);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function getDataPencarian($params){
		try{
			$dataKategori = [];
			$data = Pencarian::selectRaw('id, false editable, nama, kategori')->whereNull('dihapus_pada')->get();
			foreach ($data as $key => $value) {
				$kategori = explode(',', $value->kategori);
				foreach ($kategori as $kk => $vv) {
					$dataKategori[$kk] = Kategori::select('id','kategori')->find($vv);
				}
				$data[$key]['nama_kategori'] = implode(',',array_column($dataKategori, 'kategori'));
				$data[$key]['kategori'] = $dataKategori;
				$keyword = Keyword::where('id_pencarian','=',$value->id)->whereNull('dihapus_pada')->get()->toArray();
				$keyword = array_column($keyword, 'keyword');
				$data[$key]['keyword']  = implode(',',$keyword);
			}
			$response = new ResponseCreator(200, 'Berhasil di dapat', $data, []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function storePencarian($params){
		try{
			
			$kategori = $params->kategori;
			if(is_array($params->kategori)){
				$kategori = array_column($params->kategori, 'id');
				$kategori = implode(',',$kategori);
			}
			
			if($params->id){
				$data = Pencarian::find($params->id);
				$data->diubah_pada = Carbon::now();
				$data->kategori = $kategori;
				$data->nama = $params->nama;
				$data->save();
			}
			else{
				$data = new Pencarian();
				$data->dibuat_pada = Carbon::now();
				$data->kategori = $kategori;
				$data->nama = $params->nama;
				$data->save();
			}


			$response = new ResponseCreator(200, 'Berhasil di simpan', [], []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function hapusPencarian($params){
		try{
			$data = Pencarian::find($params->id);
			$data->dihapus_pada = Carbon::now();
			$data->save();


			$response = new ResponseCreator(200, 'Berhasil di hapus', [], []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function getDataKeyword($params){
		try{
			$dataKategori = [];
			$data = Keyword::selectRaw('id, false editable, keyword')
			->whereNull('dihapus_pada')
			->where('id_pencarian','=',$params->input('id_pencarian'))
			->get();
			$response = new ResponseCreator(200, 'Berhasil di dapat', $data, []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function getDataDokumen($params){
		try{
			$KonfigurasiDokumen = new KonfigurasiDokumen();
			$dataKategori = [];
			$data = Dokumen::selectRaw('id, file_name_generated, file_name_original, url_from')
			->whereNull('dihapus_pada')
			->where('id_pencarian','=',$params->input('id_pencarian'))
			->get();

			foreach ($data as $key => $value) {
				$data[$key]['file_exist'] = file_exists($KonfigurasiDokumen->ambilDirektoriUploads().$value->file_name_generated);
				$data[$key]['path_url'] = $KonfigurasiDokumen->ambilPathUploads().$value->file_name_generated;
			}
			$response = new ResponseCreator(200, 'Berhasil di dapat', $data, []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function storeKeyword($params){
		try{
			
			if($params->id){
				$data = Keyword::find($params->id);
				$data->diubah_pada = Carbon::now();
				$data->keyword =  $params->keyword;
				$data->save();
			}
			else{
				$data = new Keyword();
				$data->dibuat_pada = Carbon::now();
				$data->keyword = $params->keyword;
				$data->id_pencarian = $params->id_pencarian;
				$data->save();
			}


			$response = new ResponseCreator(200, 'Berhasil di simpan', [], []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function getEkstrak($params){
		try{
			
			$pythonAdapter = new PythonAdapter();
			$params = [
				'dokumen'=>$params->dokumen,
				'directory'=>env('DIREKTORI_ARTIKEL_UPLOAD'),
				'search'=>explode(',', $params->search)
			];
			$data = $pythonAdapter->ekstrakText(['data'=>json_encode($params)]);


			$response = new ResponseCreator(200, 'Berhasil di dapat', json_decode($data), []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function hapusKeyword($params){
		try{
			$data = Keyword::find($params->id);
			$data->dihapus_pada = Carbon::now();
			$data->save();


			$response = new ResponseCreator(200, 'Berhasil di hapus', [], []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function simpanFile($params){
		try{
			$contents = file_get_contents($params->url);
				$pattern = "/^content-type\s*:\s*(.*)$/i";
				if (($header = array_values(preg_grep($pattern, $http_response_header))) &&
				    (preg_match($pattern, $header[0], $match) !== false))
				{
				    $content_type = $match[1];
				}

				if($content_type != 'application/pdf'){
					$response = new ResponseCreator(203, 'Dokumen harus di upload secara manual', ['success'=>false], []);
					return $response->getResponse();
				}

			$name_generated = $this->generateRandomString();
			$file_name_generated = date('YmdHis') . '_'.$name_generated.'.pdf';
			$file_name_original = basename($params->url);

			$simpan = file_put_contents('public/uploads/'.$file_name_generated, $contents);

			if($simpan){
				$dokumen = new Dokumen();
				$dokumen->dibuat_pada = Carbon::now();
				$dokumen->file_name_original = $file_name_original;
				$dokumen->id_pencarian = $params->id_pencarian;
				$dokumen->file_name_generated = $file_name_generated;
				$dokumen->url_from = $params->url;
				$dokumen->save();
			}

			$response = new ResponseCreator(200, 'Berhasil di simpan', ['success'=>true], []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function simpanDokumenArtikel($params){
		try{
			 $file = $params->file('file');
			 $ekstensi = $file->getClientOriginalExtension();
	            if ($ekstensi !='pdf' && $ekstensi !='PDF') {
	            
	                $response = new ResponseCreator(200, 'File yang anda masukkan tidak valid', ['status'=>false], []);
	                return $response->getResponse();
	            }

	        $folder = 'public/uploads/';
			$namaFileAsli = $file->getClientOriginalName();
           	$dokumen = date('YmdHis') . '_' . $this->generateRandomString();
           	$dokumen = str_replace(' ', '_',$dokumen);
           	$dokumen =$dokumen . "." . $ekstensi;
            $file->move($folder,$dokumen);

            $tbDokumen = new Dokumen();
            $tbDokumen->dibuat_pada = Carbon::now();
			$tbDokumen->file_name_original = $namaFileAsli;
			$tbDokumen->id_pencarian = $params->id_pencarian;
			$tbDokumen->file_name_generated = $dokumen;
			$tbDokumen->save();

			$response = new ResponseCreator(200, 'Berhasil di simpan', ['status'=>true], []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}

	public function hapusDokumen($params){
		try{
			$data = Dokumen::find($params->id);
			$data->dihapus_pada = Carbon::now();
			$data->save();


			$response = new ResponseCreator(200, 'Berhasil di hapus', [], []);

		}
		catch(\Exception $e){
			$error[] = $e->getMessage();
			$response = new ResponseCreator(500, 'Terjadi Kesalahan pada server', [], $error);
		}
		return $response->getResponse();
	}



	
}
?>