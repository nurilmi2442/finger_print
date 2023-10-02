import httpClient from './httpClient';

// you can pass arguments to use as request parameters/data
const getGedung = (params) => httpClient.get('/master/get-gedung', { params });
// maybe more than one..
const getLokasi = (params) => httpClient.get('/master/get-lokasi', {params});
const hapusLokasi = (params) => httpClient.post('/master/delete-lokasi', params);

const simpanLokasi = (params) => httpClient.post('/master/simpan-lokasi', params);
const simpanGedung = (params) => httpClient.post('/master/simpan-gedung', params);
const hapusGedung = (params) => httpClient.post('/master/delete-gedung', params);

const simpanProyek = (params) => httpClient.post('/master/simpan-proyek', params);
const getProyek = (params) => httpClient.get('/master/get-proyek', { params });
const hapusProyek = (params) => httpClient.post('/master/delete-proyek', params);

const getUser = (params) => httpClient.get('/master/user', { params });
const simpanUser = (params) => httpClient.post('/master/simpan-user', params);
const hapusUser = (params) => httpClient.post('/master/delete-user', params);

export {
    getGedung,
    getLokasi,
    hapusLokasi,
    simpanLokasi,
    simpanGedung,
    hapusGedung,
    getUser,
    simpanUser,
    hapusUser,
    simpanProyek,
    getProyek,
    hapusProyek
}