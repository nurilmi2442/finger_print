<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Repositories\Eloquent\CekRepo;
use App\Repositories\Eloquent\TransaksiRepo;
use App\Repositories\Eloquent\MasterRepo;

class CekController extends Controller
{
    private $CekRepo;
    public function __construct(CekRepo $CekRepo)
    {
        $this->middleware("auth");
        $this->CekRepo = $CekRepo;
    }

    public function CekLpbg(Request $request){
        $params=[
            'page'=>$request->input('page'),
            'search'=>$request->input('search')
        ];

        $cek = $this->CekRepo->CekLpbg($params);

        if($request->ajax()){
            return response()->json($cek, $cek['code'] );
        }

        return Inertia::render('Cek/CekLpbg',[
            'data'=>$cek['data']
        ]);
    }

    public function CekSttp(Request $request){
        $params=[
            'page'=>$request->input('page'),
            'search'=>$request->input('search'),
            'start'=>$request->input('start'),
            'end'=>$request->input('end')
        ];

        $cek = $this->CekRepo->CekSttp($params);

        // print_r($cek);
        // exit;
        if($request->ajax()){
            return response()->json($cek, $cek['code'] );
        }

        return Inertia::render('Cek/CekSttp',[
            'data'=>$cek['data']
        ]);
    }

}
