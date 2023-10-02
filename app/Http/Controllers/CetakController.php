<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Adapter\ImsAdapter;
use PDF;
use App\Models\SttpHdr;
use App\Models\SttpDtl;

class CetakController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function sttp(Request $request)
    {
       $id = $request->input('sttphdr_id');
       $id = base64_decode($id);

       $SttpHdr = SttpHdr::selectRaw("sttphdr.*, date_format(tanggal, '%d/%m/%Y') tgl, lokasi, gedung, sttphdr.kode_proyek, nama_proyek")
       ->leftJoin('lokasi','lokasi.id','=','SttpHdr.lokasi_id')
       ->leftJoin('gedung','gedung.id','=','SttpHdr.gedung_id')
       ->leftJoin('proyek','proyek.kode_proyek','=','SttpHdr.kode_proyek')
       ->find($id);


       $sttpdtl = SttpDtl::where('sttphdr_id','=',$id)
       ->leftJoin('lpbgdtl','lpbgdtl.id','=','sttpdtl.lpbgdtl_id')
       ->leftJoin('lpbghdr','lpbghdr.id','=','lpbgdtl.lpbghdr_id')
       ->leftJoin('material','material.id','=','lpbgdtl.material_id')
       ->selectRaw('material.kode_material, lpbghdr.no_lpbg, material.deskripsi, lpbgdtl.qty, material.satuan')
       ->get();


       $pdf = PDF::loadview('pdf/sttp',['item'=>$sttpdtl,'sttp'=>$SttpHdr]);
       return $pdf->stream("dompdf_out.pdf", array("Attachment" => false));

       // return $pdf->download('laporan-pegawai-pdf.pdf');
    }

   
}
