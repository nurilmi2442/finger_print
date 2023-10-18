<?php

namespace App\Http\Controllers;

use App\Models\Log;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;


class LogController extends Controller
{
    public function pageLog(Request $request)
    {
        $log = DB::table('datamesin')
        ->leftJoin('device_log', 'datamesin.sn', '=', 'device_log.sn')
        ->leftJoin('sites', 'datamesin.id_sites', '=', 'sites.id')
        ->select('device_log.sn', 'datamesin.ip as ip_mesin', 'sites.nama as site','device_log.data' ,'device_log.date', 'device_log.url')
        ->orderBy('device_log.date', 'desc');

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if($start_date && $end_date){
            $log=$log->whereDate('date', '>=', $start_date)
            ->whereDate('date', '<=', $end_date);
        }

        if($request->search){
            $log=$log->where('sites.nama', 'LIKE', '%'.$request->search.'%')
            ->orWhere('datamesin.ip', 'LIKE', '%'.$request->search.'%')
            ->orWhere('data', 'LIKE', '%'.$request->search.'%')
            ->orWhere('device_log.sn', 'LIKE', '%'.$request->search.'%')
            ->orWhere('url', 'LIKE', '%'.$request->search.'%');
        }


        $log = $log->paginate(10);


        if ($request->ajax()) {
            return response()->json(['log' => $log, 'message' => 'Berhasil di dapat']);
        }

        return Inertia::render('Finger/Log', [
            'log' =>$log
        ]);
    }

}
