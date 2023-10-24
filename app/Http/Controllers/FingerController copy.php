<?php

namespace App\Http\Controllers;

use App\Models\Datamesin;
use App\Models\Sites;
use App\Models\Departemen;
use App\Models\Datashift;
use App\Models\Groupshift;
use App\Models\Datapegawai;
use App\Models\Schedulemaster;

use App\Models\Datashift as ModelsDatashift;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Repositories\Eloquent\SoContractRepo;
use Dflydev\DotAccessData\Data;

class FingerController extends Controller
{
   public  function __construct()
    {
        $this->middleware("auth");
    }

    //data-sites

    public function pageSites(Request $request)
    {
        $search = $request->input('search');

        $title = 'sites';
        $sites = Sites::select('id', 'nama');

        $sites = $sites->paginate(5);

        if ($request->ajax()) {
            return response()->json(['data' => $sites, 'message' => 'Berhasil di dapat']);
        }

        //button search
        if ($search) {
            $sites->where(function ($query) use ($search) {
                $columns = ['nama'];
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%' . $search . '%');
                }
            });
        }

        return Inertia::render('Finger/Sites', [
            'site' => $sites
        ]);
    }

    public function simpanSites(Request $request)
    {


        if ($request->input('id')) {

            $sites = Sites::where("id", $request->input('id'))->first();
            $sites->update([
                'nama' => $request->input('nama'),
            ]);
        } else {
            $validatedData = $request->validate([
                'nama' => 'required',
            ]);
            $proyeks = Sites::create(
                [
                    'nama' => $validatedData['nama'],
                ]
            );
        }

        return response()->json(['message' => 'Data user berhasil disimpan']);
    }

    public function hapusSites(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
        ]);

        $sites= Sites::findOrFail($validatedData['id']);

        $sites->delete();

        return response()->json(['message' => 'Data proyek berhasil dihapus']);
    }



    //data-mesin

    public function datamesin()
    {

        return Inertia::render('Finger/Datamesin');
    }

    public function pageDatamesin(Request $request)
    {
        $search = $request->input('search');

        $title = 'datamesin';

        $datamesin = Datamesin::select('datamesin.id', 'ip', 'mac_address', 'comkey', 'status', 'id_sites', 'lokasi','sites.nama','datamesin.sn','last_updated')
        ->selectRaw(" case when status = 1 then 'Aktif' when status = 0 then 'Tidak Aktif' else null end as status_nama ")
        ->leftJoin('sites', 'sites.id', '=', 'datamesin.id_sites');

        $sites = Sites::select('id', 'nama')->get();

        //button search
        if ($search) {
            $datamesin->where(function ($query) use ($search) {
                $columns = ['ip', 'mac_address', 'comkey', 'status', 'nama', 'lokasi'];
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%' . $search . '%');
                }
            });
        }

        $datamesin = $datamesin->paginate(5);

        if ($request->ajax()) {
            return response()->json(['data' => $datamesin, 'message' => 'Berhasil di dapat']);
        }

        return Inertia::render('Finger/Datamesin', [
            'datamesin' => $datamesin,
            'sites' => $sites

        ]);
    }

    public function simpanDatamesin(Request $request)
    {


        if ($request->input('id')) {

            $datamesin = Datamesin::where("id", $request->input('id'))->first();
            $datamesin->update([
                'ip' => $request->input('ip'),
                'mac_address' => $request->input('mac_address'),
                'comkey' => $request->input('comkey'),
                'status' => $request->input('status'),
                'id_sites' => $request->input('id_sites'),
                'lokasi' => $request->input('lokasi'),
                'sn' => $request->input('sn'),
            ]);
        } else {
            $validatedData = $request->validate([
                'ip' => 'required',
                'mac_address' => 'required',
                'comkey' => 'required',
                'status' => 'required',
                'id_sites' => 'required',
                'lokasi' => 'required',
                'sn' => 'required',
            ]);
            $datamesin = Datamesin::create(
                [
                    'ip' => $validatedData['ip'],
                    'mac_address' => $validatedData['mac_address'],
                    'comkey' => $validatedData['comkey'],
                    'status' => $validatedData['status'],
                    'id_sites' => $validatedData['id_sites'],
                    'lokasi' => $validatedData['lokasi'],
                    'sn' => $validatedData['sn'],
                ]
            );
        }

        return response()->json(['message' => 'Data user berhasil disimpan']);
    }

    public function hapusDatamesin(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
        ]);

        $datamesin= Datamesin::findOrFail($validatedData['id']);

        $datamesin->delete();

        return response()->json(['message' => 'Data proyek berhasil dihapus']);
    }


    //Departemen
    public function pageDepartemen(Request $request)
    {
        $search = $request->input('search');

        $title = 'departemen';
        $departemen = Departemen::select('id', 'id_dept', 'nama_departemen', 'status')
        ->selectRaw(" case when status = 1 then 'Aktif' when status = 0 then 'Tidak Aktif' else null end as status_nama ");

        $departemen = $departemen->paginate(5);

        if ($request->ajax()) {
            return response()->json(['data' => $departemen, 'message' => 'Berhasil di dapat']);
        }

        //button search
        if ($search) {
            $departemen->where(function ($query) use ($search) {
                $columns = ['id_dept', 'nama_departemen', 'status'];
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%' . $search . '%');
                }
            });
        }

        return Inertia::render('Finger/Departemen', [
            'departemen' => $departemen
        ]);
    }


    public function simpanDepartemen(Request $request)
    {

        if ($request->input('id')) {

            $departemen = Departemen::where("id", $request->input('id'))->first();
            $departemen->update([
                'id_dept' => $request->input('id_dept'),
                'nama_departemen' => $request->input('nama_departemen'),
                'status' => $request->input('status'),
            ]);
        } else {
            $validatedData = $request->validate([
                'id_dept' => 'required',
                'nama_departemen' => 'required',
                'status' => 'required',
            ]);
            $departemen = Departemen::create(
                [
                    'id_dept' => $validatedData['id_dept'],
                    'nama_departemen' => $validatedData['nama_departemen'],
                    'status' => $validatedData['status'],
                ]
            );
        }

        return response()->json(['message' => 'Data user berhasil disimpan']);
    }

    public function hapusDepartemen(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
        ]);

        $departemen= Departemen::findOrFail($validatedData['id']);

        $departemen->delete();

        return response()->json(['message' => 'Data proyek berhasil dihapus']);
    }

        //Data shift
        public function pageDatashift(Request $request)
        {
            $search = $request->input('search');

            $title = 'datashift';
            $datashift = Datashift::select('id', 'nama', 'start_masuk', 'masuk', 'end_masuk', 'start_keluar', 'keluar', 'end_keluar', 'break_masuk', 'break_keluar');

            $datashift = $datashift->paginate(5);

            if ($request->ajax()) {
                return response()->json(['data' => $datashift, 'message' => 'Berhasil di dapat']);
            }

            //button search
            if ($search) {
                $datashift->where(function ($query) use ($search) {
                    $columns = ['nama', 'start_masuk', 'masuk', 'end_masuk', 'start_keluar', 'keluar', 'end_keluar', 'break_masuk', 'break_keluar'];
                    foreach ($columns as $column) {
                        $query->orWhere($column, 'like', '%' . $search . '%');
                    }
                });
            }

            return Inertia::render('Finger/Datashift', [
                'datashift' => $datashift
            ]);
        }

        public function simpanDatashift(Request $request)
    {

        if ($request->input('id')) {

            $datashift = Datashift::where("id", $request->input('id'))->first();
            $datashift->update([
                'nama' => $request->input('nama'),
                'start_masuk' => $request->input('start_masuk'),
                'masuk' => $request->input('masuk'),
                'end_masuk' => $request->input('end_masuk'),
                'start_keluar' => $request->input('start_keluar'),
                'keluar' => $request->input('keluar'),
                'end_keluar' => $request->input('end_keluar'),
                'break_masuk' => $request->input('break_masuk'),
                'break_keluar' => $request->input('break_keluar'),

            ]);
        } else {
            $validatedData = $request->validate([
                'nama' => 'required',
                'start_masuk' => 'required',
                'masuk' => 'required',
                'end_masuk' => 'required',
                'start_keluar' => 'required',
                'keluar' => 'required',
                'end_keluar' => 'required',
                'break_masuk' => 'required',
                'break_keluar' => 'required',
            ]);
            $datashift = Datashift::create(
                [
                    'nama' => $validatedData['nama'],
                    'start_masuk' => $validatedData['start_masuk'],
                    'masuk' => $validatedData['masuk'],
                    'end_masuk' => $validatedData['end_masuk'],
                    'start_keluar' => $validatedData['start_keluar'],
                    'keluar' => $validatedData['keluar'],
                    'end_keluar' => $validatedData['end_keluar'],
                    'break_masuk' => $validatedData['break_masuk'],
                    'break_keluar' => $validatedData['break_keluar'],
                ]
            );
        }

        return response()->json(['message' => 'Data user berhasil disimpan']);
    }

    public function hapusDatashift(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
        ]);

        $datashift= Datashift::findOrFail($validatedData['id']);

        $datashift->delete();

        return response()->json(['message' => 'Data proyek berhasil dihapus']);
    }


    //Data Pegawai
    public function pageDatapegawai(Request $request)
    {
        $search = $request->input('search');

        $title = 'datapegawai';
        $datapegawai = Datapegawai::select('datapegawai.id', 'nama', 'id_departemen', 'departemen.nama_departemen')
        ->leftJoin('departemen', 'departemen.id', '=', 'datapegawai.id_departemen');

        $departemen = Departemen::select('id', 'nama_departemen')->get();

        $datapegawai = $datapegawai->paginate(5);

        //button search
        if ($search) {
            $datapegawai->where(function ($query) use ($search) {
                $columns = ['nama', 'id_departemen'];
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%' . $search . '%');
                }
            });
        }

        if ($request->ajax()) {
            return response()->json(['data' => $datapegawai, 'message' => 'Berhasil di dapat']);
        }

        return Inertia::render('Finger/Datapegawai', [
            'datapegawai' => $datapegawai,
            'departemen' => $departemen
        ]);
    }

    public function simpanDatapegawai(Request $request)
    {

        if ($request->input('id')) {

            $datapegawai = Datapegawai::where("id", $request->input('id'))->first();
            $datapegawai->update([
                'nama' => $request->input('nama'),
                'id_departemen' => $request->input('id_departemen'),
            ]);

        } else {
            $validatedData = $request->validate([
                'nama' => 'required',
                'id_departemen' => 'required',
            ]);
            $datashift = Datapegawai::create(
                [
                    'nama' => $validatedData['nama'],
                    'id_departemen' => $validatedData['id_departemen'],
                ]
            );
        }

        return response()->json(['message' => 'Data user berhasil disimpan']);
    }

    public function hapusDatapegawai(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
        ]);

        $datapegawai= Datapegawai::findOrFail($validatedData['id']);

        $datapegawai->delete();

        return response()->json(['message' => 'Data proyek berhasil dihapus']);
    }

    //GROUP SHIFT
    public function pageGroupshift(Request $request)
    {
        $search = $request->input('search');

        $title = 'groupshift';
        $groupshift = Groupshift::select('groupshift.id', 'nama_group', 'id_pegawai', 'datapegawai.nama')
        ->leftJoin('datapegawai', 'datapegawai.id', '=', 'groupshift.id_pegawai');

        $datapegawai = Datapegawai::select('id', 'nama')->get();

        $groupshift = $groupshift->paginate(5);

        //button search
        if ($search) {
            $groupshift->where(function ($query) use ($search) {
                $columns = ['nama_group', 'id_pegawai'];
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%' . $search . '%');
                }
            });
        }

        if ($request->ajax()) {
            return response()->json(['data' => $groupshift, 'message' => 'Berhasil di dapat']);
        }

        return Inertia::render('Finger/Groupshift', [
            'groupshift' => $groupshift,
            'datapegawai' => $datapegawai
        ]);
    }

    public function simpanGroupshift(Request $request)
    {

        if ($request->input('id')) {

            $groupshift = Groupshift::where("id", $request->input('id'))->first();
            $groupshift->update([
                'nama_group' => $request->input('nama_group'),
                'id_pegawai' => $request->input('id_pegawai'),
            ]);

        } else {
            $validatedData = $request->validate([
                'nama_group' => 'required',
                'id_pegawai' => 'required',
            ]);
            $datashift =Groupshift::create(
                [
                    'nama_group' => $validatedData['nama_group'],
                    'id_pegawai' => $validatedData['id_pegawai'],
                ]
            );
        }

        return response()->json(['message' => 'Data user berhasil disimpan']);
    }

    public function hapusGroupshift(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
        ]);

        $groupshift= Groupshift::findOrFail($validatedData['id']);

        $groupshift->delete();

        return response()->json(['message' => 'Data proyek berhasil dihapus']);
    }

    //SCHEDULE MASTER

    public function pageSchedulemaster(Request $request)
    {
        $project = $request->query('nama_pegawai');
        $shift = $request->query('nama_groupshift');
        // $search = $request->input('search');

        $title = 'schedulemaster';
        $schedulemaster = Schedulemaster::select('datapegawai.nama', 'departemen.nama_departemen', 'groupshift.nama_group', 'datashift.nama', '')
        ->leftJoin('datapegawai', 'datapegawai.id', '=', 'schedulemaster.nama_pegawai')
        ->leftJoin('departemen', 'departemen.id', '=', 'schedulemaster.nama_departemen')
        ->leftJoin('datashift', 'datashift.id', '=', 'schedulemaster.nama_shift')
        ->leftJoin('groupshift', 'groupshift.id', '=', 'schedulemaster.nama_groupshift');

        $tabel = Departemen::select('nama_departemen', 'dp.nama', 'gs.nama_group')
        ->leftJoin('datapegawai as dp', 'departemen.id', '=', 'dp.id_departemen')
        ->leftJoin('groupshift as gs', 'gs.id_pegawai', '=', 'dp.id');

        $tabel2 = Datapegawai::selectRaw('nama,selected_date')
        ->crossJoin(DB::raw("
            (
                select * from
                    (select adddate('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) selected_date from
                    (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
                    (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
                    (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
                    (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
                    (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) v
                    where selected_date between '2023-09-01' and '2023-09-03'
            ) as t
        "))
        // ->leftJoin('groupshift as gs', 'gs.id_pegawai', '=', 'datapegawai.id')
        ->orderBy('nama','asc')
        ->orderBy('selected_date','asc');

        $departemen = Departemen::select('id', 'nama_departemen')->get();
        $datapegawai = Datapegawai::select('id', 'nama')->get();
        $datashift = Datashift::select('id', 'nama',)->get();
        $groupshift = Groupshift::select('id', 'nama_group',)->get();

        if($project){
            $tabel2 = $tabel2->where('id', '=', $project);
        }

        if($shift){
            $tabel2 = $tabel2->where('gs.id', '=', $shift);
        }
        $tabel2 = $tabel2 ->paginate(5);

        if ($request->ajax()) {
            return response()->json([
                'schedulemaster' => $tabel,
                'schedulemaster' => $tabel2,
                'datapegawai' => $datapegawai,
                'departemen' => $departemen,
                'datashift' => $datashift,
                'groupshift' => $groupshift
            , 'message' => 'Berhasil di dapat']);
        }

        return Inertia::render('Finger/Schedulemaster', [
            'schedulemaster' => $tabel2,
            'datapegawai' => $datapegawai,
            'departemen' => $departemen,
            'datashift' => $datashift,
            'groupshift' => $groupshift
        ]);
    }

    public function hapusSchedulemaster(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
        ]);

        $schedulemaster= Schedulemaster::findOrFail($validatedData['id']);

        $schedulemaster->delete();

        return response()->json(['message' => 'Data proyek berhasil dihapus']);
    }



    //SCHEDULE
    public function pageSchedule(Request $request)
    {
        $sites = Sites::select('id', 'nama');

        $sites = $sites->paginate(5);

        if ($request->ajax()) {
            return response()->json(['data' => $sites, 'message' => 'Berhasil di dapat']);
        }

        return Inertia::render('Finger/Schedule', [
            'site' => $sites
        ]);
    }
}
