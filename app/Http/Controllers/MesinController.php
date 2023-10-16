<?php

namespace App\Http\Controllers;

use App\Models\Datamesin;
use App\Models\Sites;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MesinController extends Controller
{
    public function cek($pin, $date){

        $data = [
            'pin' => $pin,
            'date' => $date,
        ];

        $cekdata = DB::table('att_log')
        ->where('pin', $data['pin'])
        ->where('date', $data['date'])
        ->first();

        if (!$cekdata) {
            return true;
        }
        else {
           return false;
        }

    }


    public function pageDatapresensi(Request $request)
    {
        $project = $request->query('ip');

        $datamesin = Datamesin::all();
        $datasite = Sites::all();

        if($project){
            $datamesin = $datamesin->where('id', '=', $project);
        }

        return Inertia::render('Mesin/Datapresensi', [
            'datamesin' => $datamesin,
            'site' => $datasite,
        ]);

    }

    public function GetDatapresensi(Request $request)
    {
        $IP = "192.168.13.4";
        $Key = 0;

        $connect = fsockopen($IP, "80", $errno, $errstr, 1);
        if ($connect) {
            $soap_request = "<GetAttLog>
                                <ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey>
                                <Arg>
                                    <DateTime xsi:type=\"xsd:string\">" . date("Y-m-d H:i:s") . "</DateTime>
                                </Arg>
                            </GetAttLog>";
            $newLine = "\r\n";
            fputs($connect, "POST /iWsService HTTP/1.0" . $newLine);
            fputs($connect, "Content-Type: text/xml" . $newLine);
            fputs($connect, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
            fputs($connect, $soap_request . $newLine);
            $buffer = "";
            while ($Response = fgets($connect, 1024)) {
                $buffer = $buffer . $Response;
            }
        } else {
            echo "<p>Koneksi Gagal</p>";
        }

        $finger = [];

        $buffer = $this->Parse_Data($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
        $buffer = explode("\r\n", $buffer);
        for ($a = 0; $a < count($buffer); $a++) {
            $data = $this->Parse_Data($buffer[$a], "<Row>", "</Row>");
            $PIN = $this->Parse_Data($data, "<PIN>", "</PIN>");
            $date = $this->Parse_Data($data, "<date>", "</date>");

            if ($PIN !=""){

                $cekdata = $this->cek($PIN, $date);
                if ($cekdata){
                    $finger[] = [
                        'pin'=>$PIN,
                        'id_mesin'=>$request->ip,
                        'date'=>$date,
                    ];
                }

            }
        }
        DB::table('att_log')->insert($finger);

        return response()->json([
            'finger' => $finger,
            'message' => 'Berhasil di dapat']);
    }

    public function GetFingerdatabase(Request $request)
    {
        $datafingerdb = DB::table('datamesin')
        ->leftJoin('att_log', 'datamesin.id', '=', 'att_log.id_mesin')
        ->select('datamesin.ip as ip_frontend', 'att_log.id_mesin', 'att_log.nip', 'att_log.date');
        // $datafingerdb = DB::table('datamesin')
        // ->leftJoin('att_log', 'datamesin.ip', '=', 'att_log.id_mesin')
        // ->select('datamesin.ip as ip_frontend', 'datamesin.id')
        // ->groupBy('datamesin.ip', 'datamesin.id');


        if($request->ip){
            $datafingerdb=$datafingerdb->where('id_mesin', $request->ip);
        }
        $datafingerdb = $datafingerdb->get();
        return response()->json([
            'datafingerdb' => $datafingerdb
        ]);
    }
}
