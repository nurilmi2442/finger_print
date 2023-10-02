<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Repositories\Eloquent\TransaksiRepo;
use App\Repositories\Eloquent\MasterRepo;
use App\Adapter\ImsAdapter;


class TransaksiController extends Controller
{
    private $TransaksiRepo;
    public function __construct(TransaksiRepo $TransaksiRepo)
    {
        $this->middleware("auth");
        $this->TransaksiRepo = $TransaksiRepo;
    }

    public function EditSttp(Request $request){
       $id = $request->input('id');
       $data = $this->TransaksiRepo->getSttpById($id);
       $master = new MasterRepo();
       $proyek = $master->getDataProyek(['all'=>true]);
       $lokasi = $master->getDataLokasi(['all'=>true]);
       $gedung = $master->getDataGedung(['all'=>true]);
       return Inertia::render('Transaksi/EditSttp', ['data'=>$data,'proyek'=>$proyek['data'],'lokasi'=>$lokasi['data'],'gedung'=>$gedung['data']]);
    }

    public function PageBpm(Request $request)
    {   
        $params=[
            'page'=>$request->input('page'),
            'search'=>$request->input('search')
        ];

        $serviceBpm = new ImsAdapter(); 
        $res = $serviceBpm->getBpm($params);

        $res = json_decode($res);
        if($request->ajax()){
            return response()->json($res->data, $res->code );
        }
        return Inertia::render('Transaksi/Bpm',['data'=>$res->data]);
    }


    public function PageInputMaterial(Request $request){
         $data = $this->TransaksiRepo->getMaterial();

         if($request->ajax()){
            return response()->json($data, $data['code']);
         }
         return Inertia::render('Transaksi/InputMaterial',$data);
    }

    public function GetMaterial(Request $request){
         $params = [
            'search' =>$request->input('search'),
            'all'=>true
         ];

         $data = $this->TransaksiRepo->getMaterial($params);

          return response()->json($data, $data['code']);
        
    }

    public function GetMaterialLpbg(Request $request){
         $params = [
            'lpbghdr_id' =>$request->input('lpbghdr_id'),
            'kode_proyek'=>$request->input('kode_proyek'),
            'search'=>$request->input('search'),
            'is_search'=>$request->input('is_search')
         ];

         $data = $this->TransaksiRepo->GetMaterialLpbg($params);

          return response()->json($data, $data['code']);
        
    }

     public function GetMaterialSttp(Request $request){
         $params = [
            'sttphdr_id' =>$request->input('sttphdr_id'),
         ];

         $data = $this->TransaksiRepo->GetMaterialSttp($params);

          return response()->json($data, $data['code']);
        
    }


    public function PageLpbg(Request $request){
         $params = [
            'search'=>$request->input('search'),
            'page'=>$request->input('page')
         ];
         $data = $this->TransaksiRepo->getLpbg($params);
         $MasterRepo = new MasterRepo();

         $proyek = $MasterRepo->getDataProyek(['all'=>true]);
         if($request->ajax()){
            return response()->json($data, $data['code']);
         }
         return Inertia::render('Transaksi/Lpbg', ['data'=>$data,'proyek'=>$proyek]);
    }

    public function EditLpbg(Request $request){
        $id = $request->input('id');
        $data = $this->TransaksiRepo->getLpbgById($id);
         $MasterRepo = new MasterRepo();
        $proyek = $MasterRepo->getDataProyek(['all'=>true]);
        return Inertia::render('Transaksi/EditLpbg', ['data'=>$data,'proyek'=>$proyek]);
    }


    public function SimpanMaterial(Request $request){
        $params = [
            'id'=>$request->input('id'),
            'kode_material'=>$request->input('kode_material'),
            'satuan'=>$request->input('satuan'),
            'deskripsi'=>$request->input('deskripsi'),

        ];
        $res = $this->TransaksiRepo->SimpanMaterial($params);

         return response()->json($res,$res['code']);
    }

    public function SimpanMaterialLpbg(Request $request){
        $params = [
            'material_id'=>$request->input('material_id'),
            'qty'=>$request->input('qty'),
            'lpbghdr_id'=>$request->input('lpbghdr_id'),

        ];
        $res = $this->TransaksiRepo->SimpanMaterialLpbg($params);

         return response()->json($res,$res['code']);
    }

     public function SimpanMaterialSttp(Request $request){
        $params = [
            'lpbgdtl_id'=>$request->input('lpbgdtl_id'),
            'qty'=>$request->input('qty'),
            'sttphdr_id'=>$request->input('sttphdr_id'),

        ];
        $res = $this->TransaksiRepo->SimpanMaterialSttp($params);

         return response()->json($res,$res['code']);
    }

    public function SimpanLpbg(Request $request){
        $params = [
            'id'=>$request->input('id'),
            'kode_proyek'=>$request->input('kode_proyek'),
            'no_lpbg'=>$request->input('no_lpbg'),
            'tanggal'=>$request->input('tanggal'),

        ];
        $res = $this->TransaksiRepo->SimpanLpbg($params);

         return response()->json($res,$res['code']);
    }

     public function HapusMaterial(Request $request){
        $id = $request->input('id');
        $res = $this->TransaksiRepo->HapusMaterial($id);

         return response()->json($res,$res['code']);
    }

     public function HapusMaterialLpbg(Request $request){
        $id = $request->input('id');
        $res = $this->TransaksiRepo->HapusMaterialLpbg($id);

         return response()->json($res,$res['code']);
    }

       public function HapusMaterialSttp(Request $request){
        $id = $request->input('id');
        $res = $this->TransaksiRepo->HapusMaterialSttp($id);

         return response()->json($res,$res['code']);
    }

     public function HapusLpbg(Request $request){
        $id = $request->input('id');
        $res = $this->TransaksiRepo->HapusLpbg($id);

         return response()->json($res,$res['code']);
    }

        public function PageSttp(Request $request)
    {   
       
        $params=[
            'page'=>$request->input('page'),
            'search'=>$request->input('search')
        ];
   

        $data = $this->TransaksiRepo->getSttp($params);

        $master = new MasterRepo();
        $lokasi = $master->getDataLokasi(['all'=>true]);
        $gedung = $master->getDataGedung(['all'=>true]);
        $proyek = $master->getDataProyek(['all'=>true]);
        if($request->ajax()){
            return response()->json($data['data'], $data['code'] );
        }

        return Inertia::render('Transaksi/Sttp',[
            'project'=>$proyek['data'],
            'lokasi'=>$lokasi['data'],
            'gedung'=>$gedung['data'],
            'data'=>$data['data']
        ]);
    }

    public function SimpanSttp(Request $request){
        $params = [
            'id'=>$request->input('id'),
            'gedung_id'=>$request->input('gedung_id'),
            'lokasi_id'=>$request->input('lokasi_id'),
            'no_sttp'=>$request->input('no_sttp'),
            'kode_proyek'=>$request->input('kode_proyek'),
            'tanggal'=>$request->input('tanggal'),
            'nomor_kereta'=>$request->input('nomor_kereta')
        ];
        
        $res = $this->TransaksiRepo->SimpanSttp($params);
        return response()->json($res['data'],$res['code']);

    }

    public function DeleteSttp(Request $request){
        $id = $request->input('id');
        $res = $this->TransaksiRepo->DeleteSttp($id);
    }

}
