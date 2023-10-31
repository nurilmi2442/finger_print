<?php

use App\Http\Controllers\Auth\{
    LoginController,
    RegisterController,
};

use App\Http\Controllers\{
    MasterController,
    PostsController,
    DcrController,
    SoContractController,
    TransaksiController,
    CetakController,
    CekController,
    DeviceCmdController,
    UserfingerController,
    FingerController,
    MesinController,
    UsersController,
    IclockController,
    LogController,
    WorkingSchController
};
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// handshake
Route::get('/iclock/cdata', [IclockController::class, 'handshake']);
// request dari device
Route::post('/iclock/cdata', [IclockController::class, 'receiveRecords']);
Route::get('/iclock/getrequest', [IclockController::class, 'getrequest']);
Route::post('/iclock/devicecmd', [IclockController::class, 'devicecmd']);



Route::get('login', [LoginController::class, 'showLoginForm'])->name('showLoginForm')->middleware('guest');
Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('showRegisterForm')->middleware('guest');

Route::post('login', [LoginController::class, 'authenticate'])->name('login');
Route::post('register', [RegisterController::class, 'register'])->name('register');

Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('dcr')->name('dcr.')->group(function () {
    Route::get('/', [DcrController::class, 'index'])->name('index');
});

Route::prefix('so')->name('so.')->group(function () {
    Route::get('/', [SoContractController::class, 'index'])->name('index');
    Route::post('/', [SoContractController::class, 'indexPost'])->name('indexPost');
    Route::get('/getDataSo', [SoContractController::class, 'getDataSo'])->name('data');
});


Route::prefix('master')->name('master.')->group(function () {
    Route::get('/lokasi', [MasterController::class, 'PageMaster'])->name('PageMaster');
    Route::get('/get-lokasi', [MasterController::class, 'getLokasi'])->name('getLokasi');
    Route::post('/simpan-lokasi', [MasterController::class, 'simpanLokasi'])->name('simpanLokasi');
    Route::post('/delete-lokasi', [MasterController::class, 'hapusLokasi'])->name('hapusLokasi');

    Route::get('/gedung', [MasterController::class, 'PageGedung'])->name('PageGedung');
    Route::get('/get-gedung', [MasterController::class, 'getGedung'])->name('getGedung');
    Route::post('/simpan-gedung', [MasterController::class, 'simpanGedung'])->name('simpanGedung');
    Route::post('/delete-gedung', [MasterController::class, 'hapusGedung'])->name('hapusGedung');

    Route::get('/proyek', [MasterController::class, 'PageProyek'])->name('PageProyek');
    Route::get('/get-proyek', [MasterController::class, 'PageProyek'])->name('getProyek');
    Route::post('/simpan-proyek', [MasterController::class, 'simpanProyek'])->name('simpanProyek');
    Route::post('/delete-proyek', [MasterController::class, 'hapusProyek'])->name('hapusProyek');

    Route::get('/user', [MasterController::class, 'PageUser'])->name('PageUser');
     Route::post('/simpan-user', [MasterController::class, 'simpanUser'])->name('simpanUser');
    Route::post('/delete-user', [MasterController::class, 'hapusUser'])->name('hapusUser');
});

Route::prefix('transaksi')->name('transaksi.')->group(function () {
    Route::get('/bpm', [TransaksiController::class, 'PageBpm'])->name('bpm');
    Route::get('/sttp', [TransaksiController::class, 'PageSttp'])->name('sttp');
    Route::post('/sttp/simpan', [TransaksiController::class, 'SimpanSttp'])->name('simpan_sttp');
    Route::post('/sttp/delete', [TransaksiController::class, 'DeleteSttp'])->name('delete_sttp');
    Route::get('/sttp/edit', [TransaksiController::class, 'EditSttp'])->name('edit_sttp');

    Route::get('/input_material', [TransaksiController::class, 'PageInputMaterial'])->name('input_material');
    Route::get('/get_material', [TransaksiController::class, 'GetMaterial'])->name('get_material');
    Route::get('/get_material_lpbg', [TransaksiController::class, 'GetMaterialLpbg'])->name('get_material_lpbg');
    Route::get('/get_material_sttp', [TransaksiController::class, 'GetMaterialSttp'])->name('get_material_sttp');
    Route::post('/material/simpan', [TransaksiController::class, 'SimpanMaterial'])->name('simpan_material');
    Route::post('/material/simpan_material_lpbg', [TransaksiController::class, 'SimpanMaterialLpbg'])->name('simpan_material_lpbg');
    Route::post('/material/simpan_material_sttp', [TransaksiController::class, 'SimpanMaterialSttp'])->name('simpan_material_sttp');
    Route::post('/material/hapus', [TransaksiController::class, 'HapusMaterial'])->name('hapus_material');
    Route::post('/material/hapus_material_lpbg', [TransaksiController::class, 'HapusMaterialLpbg'])->name('hapus_material_lpbg');
    Route::post('/material/hapus_material_sttp', [TransaksiController::class, 'HapusMaterialSttp'])->name('hapus_material_sttp');
    Route::post('/material/hapus_lpbg', [TransaksiController::class, 'HapusLpbg'])->name('hapus_lpbg');

    Route::get('/lpbg', [TransaksiController::class, 'PageLpbg'])->name('lpbg');
    Route::get('/lpbg/edit', [TransaksiController::class, 'EditLpbg'])->name('edit_lpbg');
    Route::post('/lpbg/simpan', [TransaksiController::class, 'SimpanLpbg'])->name('simpan_lpbg');
});

Route::prefix('cetak-pdf')->name('cetakpdf.')->group(function () {
    Route::get('sttp', [CetakController::class, 'sttp'])->name('cetaksttp');
});

Route::prefix('cek')->name('cek.')->group(function () {
    Route::get('cek-by-lpbg', [CekController::class, 'CekLpbg'])->name('ceklpbg');
    Route::get('cek-by-sttp', [CekController::class, 'CekSttp'])->name('ceksttp');
});


Route::resource('post', PostsController::class);



Route::redirect('/', 'so');

Route::prefix('device')->name('device.')->group(function () {
    Route::get('/sites', [FingerController::class, 'pageSites'])->name('pageSites');
    Route::get('/get-sites', [FingerController::class, 'pageSites'])->name('getsites');
    Route::post('/simpan-sites', [FingerController::class, 'simpanSites'])->name('simpansites');
    Route::post('/del-sites', [FingerController::class, 'hapusSites'])->name('hapussites');

    Route::get('/datamesin', [FingerController::class, 'pageDatamesin'])->name('pageDatamesin');
    Route::get('/get-datamesin', [FingerController::class, 'pageDatamesin'])->name('getdatamesin');
    Route::post('/simpan-datamesin', [FingerController::class, 'simpanDatamesin'])->name('simpandatamesin');
    Route::post('/del-datamesin', [FingerController::class, 'hapusDatamesin'])->name('hapusdatamesin');

    Route::get('/log', [LogController::class, 'pageLog'])->name('pageLog');
    Route::get('/get-log', [LogController::class, 'pageLog'])->name('getlog');

    Route::get('/devicecmd', [MesinController::class, 'pageDeviceCmd'])->name('pageDeviceCmd');
    Route::get('/get-device', [MesinController::class, 'pageDeviceCmd'])->name('getdevice');
    Route::post('/del-device', [MesinController::class, 'hapusDevicecmd'])->name('hapusdevicecmd');

});

Route::prefix('finger')->name('finger.')->group(function () {
    Route::get('/departemen', [FingerController::class, 'pageDepartemen'])->name('pageDepartemen');
    Route::get('/get-departemen', [FingerController::class, 'pageDepartemen'])->name('getDepartemen');
    Route::post('/simpan-departemen', [FingerController::class, 'simpanDepartemen'])->name('simpanDepartemen');
    Route::post('/del-departemen', [FingerController::class, 'hapusDepartemen'])->name('hapusDepartemen');

    Route::get('/datashift', [FingerController::class, 'pageDatashift'])->name('pageDatashift');
    Route::get('/get-datashift', [FingerController::class, 'pageDatashift'])->name('getDatashift');
    Route::post('/simpan-datashift', [FingerController::class, 'simpanDatashift'])->name('simpanDatashift');
    Route::post('/del-datashift', [FingerController::class, 'hapusDatashift'])->name('hapusDatashift');

    Route::get('/datapegawai', [FingerController::class, 'pageDatapegawai'])->name('pageDatapegawai');
    Route::get('/get-datapegawai', [FingerController::class, 'pageDatapegawai'])->name('getDatapegawai');
    Route::post('/simpan-datapegawai', [FingerController::class, 'simpanDatapegawai'])->name('simpanDatapegawai');
    Route::post('/del-datapegawai', [FingerController::class, 'hapusDatapegawai'])->name('hapusDatapegawai');

    Route::get('/groupshift', [FingerController::class, 'pageGroupshift'])->name('pageGroupshift');
    Route::get('/get-groupshift', [FingerController::class, 'pageGroupshift'])->name('getGroupshift');
    Route::post('/simpan-groupshift', [FingerController::class, 'simpanGroupshift'])->name('simpanGroupshift');
    Route::post('/del-groupshift', [FingerController::class, 'hapusGroupshift'])->name('hapusGroupshift');

    Route::get('/schedulemaster', [FingerController::class, 'pageSchedulemaster'])->name('pageSchedulemaster');
    Route::get('/get-schedulemaster', [FingerController::class, 'pageSchedulemaster'])->name('getSchedulemaster');
    Route::get('/get-pegawai', [FingerController::class, 'GetPegawai'])->name('GetPegawai');
    Route::post('/simpan-workingsch', [FingerController::class, 'simpanData'])->name('GetPegawai');


    Route::get('/users', [UsersController::class, 'pageUsers'])->name('pageUsers');
    Route::get('/get-users', [UsersController::class, 'pageUsers'])->name('getUsers');
    Route::post('/simpan-users', [UsersController::class, 'simpanUsers'])->name('simpanUsers');
    Route::post('/del-users', [UsersController::class, 'hapusUsers'])->name('hapusUsers');


    Route::get('/schedule', [FingerController::class, 'pageSchedule'])->name('pageSchedule');
});

Route::prefix('mesin')->name('mesin.')->group(function () {
    Route::get('/datapresensi', [MesinController::class, 'pageDatapresensi'])->name('pageDatapresensi');
    Route::get('/get-datapresensi', [MesinController::class, 'GetDatapresensi'])->name('GetDatapresensi');
    Route::get('/get-datafingerdatabase', [MesinController::class, 'GetFingerdatabase'])->name('GetDatapresensi');

    Route::get('/datauserfinger', [UserfingerController::class, 'pageUserfinger'])->name('pageUserfinger');
    Route::get('/get-datauserfinger', [UserfingerController::class, 'GetUserfinger'])->name('GetUserfinger');
    Route::get('/get-userfingerdatabase', [UserfingerController::class, 'GetUserdatabase'])->name('GetUserfinger');
    Route::post('/simpan-uploaduser', [UserfingerController::class, 'uploadUser'])->name('uploadUser');
    Route::post('/del-user', [UserfingerController::class, 'hapusUser'])->name('hapusUser');

    Route::post('/upload', [UserfingerController::class, 'uploadData'])->name('uploadData');

    Route::get('/get-synchornizefinger', [UserfingerController::class, 'Getfinger'])->name('Getfinger');
});
