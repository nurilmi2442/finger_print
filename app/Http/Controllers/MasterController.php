<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Repositories\Eloquent\MasterRepo;


class MasterController extends Controller
{
    private $MasterRepo;
    public function __construct(MasterRepo $MasterRepo)
    {
        $this->middleware("auth");
        $this->MasterRepo = $MasterRepo;
    }

    public function hapusLokasi(Request $request){
        $id = $request->input('id');
        $res = $this->MasterRepo->hapusLokasi($id);
    }

    public function hapusGedung(Request $request){
        $id = $request->input('id');
        $res = $this->MasterRepo->hapusGedung($id);
    }

     public function hapusUser(Request $request){
        $id = $request->input('id');
        $res = $this->MasterRepo->hapusUser($id);
    }

    public function PageMaster()
    {   
        $data = $this->MasterRepo->getDataLokasi();

        return Inertia::render('Master/Lokasi',['data'=>$data]);
    }

    public function PageProyek(Request $request)
    {   
        $data = $this->MasterRepo->getDataProyek();

        if($request->ajax()){
            return response()->json($data,$data['code']);
        }
        return Inertia::render('Master/Proyek',['data'=>$data]);
    }

     public function PageUser(Request $request)
    {   
        $data = $this->MasterRepo->getDataUser();
         if($request->ajax()){
            return response()->json($data, $data['code']);
        }
        return Inertia::render('Master/User',['data'=>$data]);
    }

      public function PageGedung()
    {   
        $data = $this->MasterRepo->getDataGedung();
        return Inertia::render('Master/Gedung',['data'=>$data]);
    }

    public function getLokasi(){
        $res= $this->MasterRepo->getDataLokasi();
        return response()->json($res, $res['code']);
    }

     public function getGedung(){
        $res= $this->MasterRepo->getDataGedung();
        return response()->json($res, $res['code']);
    }

    public function simpanLokasi(Request $request){
        $res = $this->MasterRepo->simpanLokasi($request);
        return response()->json($res, $res['code']);
    }

    public function simpanProyek(Request $request){
        $params = [
            'id'=>$request->input('id'),
            'kode_proyek'=>$request->input('kode_proyek'),
            'nama_proyek'=>$request->input('nama_proyek')
        ];
        $res = $this->MasterRepo->simpanProyek($params);
        return response()->json($res, $res['code']);
    }

    public function simpanGedung(Request $request){
        $res = $this->MasterRepo->simpanGedung($request);
        return response()->json($res, $res['code']);
    }

    public function simpanUser(Request $request){
        $res = $this->MasterRepo->simpanUser($request);
        return response()->json($res, $res['code']);
    }

     public function indexPost(Request $request)
    {   
        $params = [
            'page'=>$request->page,
            'filters'=>$request->filters,
            'sortField'=>$request->sortField,
            'sortOrder'=>$request->sortOrder
        ];

        $res = $this->MasterRepo->getSoContract($params);

        

        if($res['data']->code == 401){
            return redirect('logout');
        }

        return response()->json($res['data']->data, $res['data']->code);
    }

    public function getDataSo()
    {   
        $res = $this->MasterRepo->getSoContract();

        if($res['data']->code == 401){
            return redirect('logout');
        }

       return response()->json($res['data'], $res['data']->code);
    }

}
