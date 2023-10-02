import httpClient from './httpClient';

const simpandatapegawai = (params) => httpClient.post('/finger/simpan-datapegawai', params);
const getdatapegawai = (params) => httpClient.get('/finger/get-datapegawai', { params });
const hapusdatapegawai = (params) => httpClient.post('/finger/del-datapegawai', params);

export {
    simpandatapegawai,
    getdatapegawai,
    hapusdatapegawai,
}
