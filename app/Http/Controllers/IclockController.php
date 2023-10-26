<?php

namespace App\Http\Controllers;
use App\Models\Datamesin;

use Illuminate\Http\Request;
use App\GlobalClass\WebhookTest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class iclockController extends Controller
{
        /*
    * Handle the incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function __invoke(Request $request)
   {

   }

    // handshake
    public function handshake(Request $request)
    {
        try{
            $data['url'] = \Request::getRequestUri();
            $data["data"] = json_encode($request->all());
            $data["sn"] = $request->SN;
            $data['date'] = Carbon::now();
            $data["option"] = $request->option;
            DB::table('device_log')->insert($data);
            $webhook = new WebhookTest;
            $requestData = $webhook->getRequest();
            // update status device
            DB::table('datamesin')->where('sn', $request->SN)->update(['last_updated' => Carbon::now()]);
            $r = "GET OPTION FROM:%s{$request->SN}\nStamp=1565089939\nOpStamp=1565089939\nErrorDelay=30\nDelay=10\nTransTimes=00:00;14:05\nTransInterval=1\nTransFlag=1111000000\nTimeZone=7\nRealtime=1\nEncrypt=0\n";
            return $r;
        }
        catch(Throwable $e){
            $webhook = new WebhookTest;
            $requestData = $webhook->getRequest();
            $msg = $e->getMessage();

            $c = $webhook->log($msg, 'content');
            return "ERROR";
        }

    }
    // implementasi https://docs.nufaza.com/docs/devices/zkteco_attendance/push_protocol/
    // setting timezone

    // request absensi
    public function receiveRecords(Request $request)
    {
    // cek validasi device fingerprint berdasarkan serial number

    try{

        $data['url'] = \Request::getRequestUri();
        $data["data"] = json_encode($request->all());
        $data["sn"] = $request->SN;
        $data['date'] = Carbon::now();
        $data["option"] = $request->option;
            DB::table('device_log')->insert($data);
        $cek = DB::table('datamesin')->where('sn','=',$request->SN)->first();
        if(is_null($cek)){return "ERROR";}

            $webhook = new WebhookTest;
            $requestData = $webhook->getRequest();
            $content = $request->getContent();
            $arr = preg_split('/\\r\\n|\\r|,|\\n/', $content);
            $jml = count($arr);
            // $c = $webhook->log(print_r($arr,true), 'content');

            $table = $request->table;

            switch ($table) {
                case 'ATTLOG':
                    $this->AttLog($arr, $cek);
                    break;

                default:
                    # code...
                    break;
            }

            return "OK";
    }
     catch(\Exception $e){
            $webhook = new WebhookTest;
            $requestData = $webhook->getRequest();
            $msg = $e->getMessage();

            $c = $webhook->log($msg, 'content');
            return "ERROR";


    }

}


protected function AttLog($arr, $mesin){

    try{
        foreach($arr as $rey){
            // $jam = $req[1];
            $req = preg_split('/\\t\\n|\\t|,|\\n/', $rey);

            if(!empty(trim($req[0])) ){
                $nip = trim($req[0]);
                $tgl = $req[1];
                    $data = [
                        'nip'=>$nip,
                        'date'=>$tgl,
                        'id_mesin'=>$mesin->id,

                    ];

                $res = DB::table('att_log')->insert($data);

                if(config('app.INTEGRATE_LOG_ATT')){

                        $val=explode(' ',$tgl);
                        $dt = str_replace('-', '', $val[0]);
                        $tm = str_replace(':', '', $val[1]);
                        $attLog = [
                          'EmpCode' => $nip,
                          'PIN'=> $nip,
                          'Machine'=>$mesin->lokasi,
                          'Remark'=>'FINGERPRINT',
                          'ProcessInd'=>'N',
                          'CreateBy'=>'TI_IMS',
                          'dt'=>$dt,
                          'tm'=>$tm,
                          'createDt'=>Carbon::now()->format('YmdHi')
                        ];

                    DB::connection('mysql2')->table('tblattendancelog')->insert($attLog);
                }

            }
        }
    }
    catch(\Exception $e){
        throw new \Exception($e->getMessage());
    }


}

public function getrequest(Request $request)
{
    $sn = $request->SN;
    $data = json_encode($request->all());
    $data = explode(',', $data);
    $webhook = new WebhookTest;
    $c = $webhook->log(print_r($data,true), 'content');

    // $jumlah_log = Datamesin::where('sn', $sn)->first();
    // $jumlah_log->jumlah_user=$data[1];
    // $jumlah_log->jumlah_finger=$data[2];
    // $jumlah_log->jumlah_log=$data[3];
    // $jumlah_log->save();
    // $c = "C:6:DATA UPDATE USERINFO PIN=6\tName=eko";
    // return $c;
}

public function devicecmd(Request $request)
{
    $sn = $request->SN;
    $data = json_encode($request->getContent());
    $data = explode(',', $data);
    $webhook = new WebhookTest;
    $c = $webhook->log(print_r($data,true), 'request');
    return "OK";

}
}
