<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Repositories\Eloquent\SoContractRepo;


class SoContractController extends Controller
{
    private $SoContractRepo;
    public function __construct(SoContractRepo $SoContractRepo)
    {
        $this->middleware("auth");
        $this->SoContractRepo = $SoContractRepo;
    }

    public function index()
    {   

        return Inertia::render('Home/Index');
    }

     public function indexPost(Request $request)
    {   
        $params = [
            'page'=>$request->page,
            'filters'=>$request->filters,
            'sortField'=>$request->sortField,
            'sortOrder'=>$request->sortOrder
        ];

        $res = $this->SoContractRepo->getSoContract($params);

        

        if($res['data']->code == 401){
            return redirect('logout');
        }

        return response()->json($res['data']->data, $res['data']->code);
    }

    public function getDataSo()
    {   
        $res = $this->SoContractRepo->getSoContract();

        if($res['data']->code == 401){
            return redirect('logout');
        }

       return response()->json($res['data'], $res['data']->code);
    }

}
