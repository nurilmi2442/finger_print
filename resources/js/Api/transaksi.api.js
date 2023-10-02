import httpClient from './httpClient';

// you can pass arguments to use as request parameters/data
const getBpm = (params) => httpClient.get('/transaksi/bpm', { params });
// maybe more than one..

// you can pass arguments to use as request parameters/data
const getSttp = (params) => httpClient.get('/transaksi/sttp', { params });
// maybe more than one..

const getMaterial = (params) => httpClient.get('/transaksi/input_material', { params });
const getMaterialLpbg = (params) => httpClient.get('/transaksi/get_material_lpbg', { params });
const getMaterialSttp = (params) => httpClient.get('/transaksi/get_material_sttp', { params });
const getLpbg = (params) => httpClient.get('/transaksi/lpbg', { params });
const ambilMaterial = (params) => httpClient.get('/transaksi/get_material', { params });
const simpanSttp = (params) => httpClient.post('/transaksi/sttp/simpan', params);
const simpanMaterial = (params) => httpClient.post('/transaksi/material/simpan', params);
const simpanMaterialLpbg = (params) => httpClient.post('/transaksi/material/simpan_material_lpbg', params);
const simpanMaterialSttp = (params) => httpClient.post('/transaksi/material/simpan_material_sttp', params);
const hapusMaterial = (params) => httpClient.post('/transaksi/material/hapus', params);
const hapusMaterialLpbg = (params) => httpClient.post('/transaksi/material/hapus_material_lpbg', params);
const hapusMaterialSttp = (params) => httpClient.post('/transaksi/material/hapus_material_sttp', params);
const hapusLpbg = (params) => httpClient.post('/transaksi/material/hapus_lpbg', params);
const hapusSttp = (params) => httpClient.post('/transaksi/material/hapus_sttp', params);

const deleteSttp = (params) => httpClient.post('/transaksi/sttp/delete', params);

const simpanLpbg = (params) => httpClient.post('/transaksi/lpbg/simpan', params);


export {
    getBpm,
    getLpbg,
    ambilMaterial,
    simpanMaterial,
    hapusMaterial,
    getMaterialSttp,
    hapusMaterialLpbg,
    hapusMaterialSttp,
    getMaterial,
    getMaterialLpbg,
    deleteSttp,
    simpanMaterialLpbg,
    simpanMaterialSttp,
    getSttp,
    simpanLpbg,
    simpanSttp,
    hapusLpbg
}