<?php

namespace App\Http\Controllers;

use App\Models\Datamesin;
use App\Models\Device_cmd;
use App\Models\Userfinger;
use App\Models\Sites;

use Database\Factories\UserFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use SebastianBergmann\Environment\Console;

class UserfingerController extends Controller
{
    public function cekDBuser($pin2){

        $cekdata = DB::table('user_finger')
        ->where('pin2', $pin2)
        ->first();

        if (!$cekdata) {
            return true;
        }
        else {
           return false;
        }
    }

    public function pageUserfinger (Request $request)
    {
        $project = $request->query('ip');

        $datamesin = Datamesin::all();
        $datasite = Sites::all();

        if($project){
            $datamesin = $datamesin->where('id', '=', $project);
        }
        return Inertia::render('Mesin/Datauserfinger', [
            'datamesin' => $datamesin,
            'site' => $datasite
        ]);
    }

    public function GetUserfinger(Request $request)
    {
        $IP = "192.168.13.4";
        $Key = 0;

        $connect = fsockopen($IP, "80", $errno, $errstr, 1);
        if ($connect) {
            $soap_request = "<GetUserInfo>
                                <ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey>
                                   <Arg><PIN>all</PIN></Arg>
                            </GetUserInfo>";
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
        $dataFinger =[];
        $buffer = $this->Parse_Data($buffer, "<GetUserInfoResponse>", "</GetUserInfoResponse>");
        $buffer = explode("\r\n", $buffer);
        for ($a = 0; $a < count($buffer); $a++) {
            $data = $this->Parse_Data($buffer[$a], "<Row>", "</Row>");
            $PIN = $this->Parse_Data($data, "<PIN>", "</PIN>");
            $name = $this->Parse_Data($data, "<Name>", "</Name>");
            $password = $this->Parse_Data($data, "<Password>", "</Password>");
            $group = $this->Parse_Data($data, "<Group", "</Group");
            $privilege = $this->Parse_Data($data, "<Privilege", "</Pivilege");
            $card = $this->Parse_Data($data, "<Card", "</Card");
            $pin2 = $this->Parse_Data($data, "<PIN2", "</PIN2");

            if($a == 100){
                break;
            }
            if ($PIN !=""){
                $paramsFp = [
                    'ip'=> $IP,
                    'comkey'=> '0',
                    'no_finger'=>'',
                    'pin'=>str_replace(">", "", $pin2)

                ];
                $cekdata = $this->cekDBuser(str_replace(">", "", $pin2));
                if ($cekdata){
                    $finger[] = [
                        'pin'=>$PIN,
                        'name'=>$name,
                        'password'=> $password,
                        'pin2'=>str_replace(">", "", $pin2),
                        'id_mesin'=>$request->ip,

                    ];
                }
                $dataFinger = $this->_FpfromMachine($paramsFp);
                DB::table('data_finger')->upsert($dataFinger, ['pin', 'finger_id'],
                ['template']);

            }
        }

        DB::table('user_finger')->insert($finger);

        return response()->json([
            'finger' => $finger,
            'message' => 'Berhasil di dapat'
        ]);
    }

    public function uploadUser(Request $request)
    {
        $datamesin = Datamesin::where('id', $request->ip)->first();
        $connect = fsockopen($datamesin->ip, "80", $errno, $errstr, 1);
                if ($connect) {
                    $soap_request = "<SetUserInfo><ArgComKey Xsi:type=\"xsd:integer\">" . $datamesin->comkey . "</ArgComKey><Arg><PIN>" . $request->pin2 . "</PIN><PIN2>" . $request->pin2 . "</PIN2><Name>" . $request->name . "</Name></Arg></SetUserInfo>";
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
					echo "<b>Result:</b><br>";
                    echo "<p>Koneksi Gagal</p>";
                }

                $buffer = $this->Parse_Data($buffer, "<Information>", "</Information>");
                echo $buffer;
    }


    public function hapusUser(Request $request)
    {
        $datamesin = Datamesin::where('id', $request->ip)->first();
        $connect = fsockopen($datamesin->ip, "80", $errno, $errstr, 1);
        if ($connect) {
            $soap_request = "<DeleteUser><ArgComKey xsi:type=\"xsd:integer\">" .  $datamesin->comkey . "</ArgComKey><Arg><PIN2 xsi:type=\"xsd:integer\">" . $request->pin2  . "</PIN2></Arg></DeleteUser>";
            $newLine = "\r\n";
            fputs($connect, "POST /iWsService HTTP/1.0" . $newLine);
            fputs($connect, "Content-Type: text/xml" . $newLine);
            fputs($connect, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
            fputs($connect, $soap_request . $newLine);
            $buffer = "";
            while ($Response = fgets($connect, 1024)) {
                $buffer .= $Response;
            }
        } else {
            echo "<b>Result:</b><br>";
            echo "<p>Koneksi Gagal</p>";
        }

        $buffer = $this->Parse_Data($buffer, "<DeleteUserResponse>", "</DeleteUserResponse>");
        $buffer = $this->Parse_Data($buffer, "<Information>", "</Information>");
        echo $buffer;
    }


    protected function _FpfromMachine($params){
        $connect = fsockopen($params['ip'], "80", $errno, $errstr, 1);
        if ($connect) {
            $soap_request = "<GetUserTemplate><ArgComKey xsi:type=\"xsd:integer\">" . $params['comkey'] . "</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">" . $params['pin'] . "</PIN><FingerID xsi:type=\"xsd:integer\">" . $params['no_finger'] . "</FingerID></Arg></GetUserTemplate>";
            $newLine = "\r\n";
            fputs($connect, "POST /iWsService HTTP/1.0" . $newLine);
            fputs($connect, "Content-Type: text/xml" . $newLine);
            fputs($connect, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
            fputs($connect, $soap_request . $newLine);
            $buffer = "";
            while ($Response = fgets($connect, 1024)) {
                $buffer .= $Response;
            }
        } else {
            echo "<p>Koneksi Gagal</p>";
        }

        $buffer = $this->Parse_Data($buffer, "<GetUserTemplateResponse>", "</GetUserTemplateResponse>");
        $buffer = explode("\r\n", $buffer);

        $result = [];
        for ($a = 0; $a < count($buffer); $a++){
            $data = $this->Parse_Data($buffer[$a], "<Row>", "</Row>");
            $PIN = $this->Parse_Data($data, "<PIN>", "</PIN>");
            if ($PIN!=""){
                $result[$a]['pin'] = $this->Parse_Data($data, "<PIN>", "</PIN>");
                $result[$a]['finger_id'] = $this->Parse_Data($data, "<FingerID>", "</FingerID>");
                $Size = $this->Parse_Data($data, "<Size>", "</Size>");
                $Valid = $this->Parse_Data($data, "<Valid>", "</Valid>");
                $result[$a]['template'] = $this->Parse_Data($data, "<Template>", "</Template>");
            }

        }
        return $result;
    }

    public function GetUserdatabase(Request $request)
    {
        $datauserfingerdb = DB::table('user_finger')
        ->leftJoin('data_finger', 'user_finger.pin2', '=', 'data_finger.pin')
        ->select('user_finger.pin2','user_finger.name', DB::raw('COUNT(user_finger.pin2) AS jumlah_finger'))
        ->groupBy('user_finger.pin2', 'user_finger.name');

        if($request->ip){
            $datauserfingerdb=$datauserfingerdb->where('id_mesin', $request->ip);
        }

        $datauserfingerdb = $datauserfingerdb->get();
        return response()->json([
            'datauserfingerdb' => $datauserfingerdb
        ]);
    }

    public function uploadData(Request $request)
    {
        $command = "DATA UPDATE USERINFO PIN=".$request->input('pin2')."\tName=".$request->input('name');
        $device = DB::table('datamesin')->selectRaw("id, id_sites, ip, sn")->where('id', $request->ip)->first();

        $upload = Device_cmd::create([
            'sn' => $device->sn,
            'command' => $command,
        ]);

        return response()->json([
            'data' => $upload,
            'command' => $command,
            'message' => 'Data user berhasil diupload'
        ]);
    }

}
