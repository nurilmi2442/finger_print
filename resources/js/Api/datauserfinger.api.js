import httpClient from './httpClient';

const getuserfinger= (params) => httpClient.get('/mesin/get-datauserfinger', { params });
const getuserfingerdatabase= (params) => httpClient.get('/mesin/get-userfingerdatabase', { params });
const synchornizefinger= (params) => httpClient.get('/mesin/get-synchornizefinger', { params });
const hapususer= (params) => httpClient.post('/mesin/del-user', { params });
const simpanuploaduser= (params) => httpClient.post('/mesin/simpan-uploaduser', params);
const upload= (params) => httpClient.post('/mesin/upload', params);

export {
    getuserfinger,
    synchornizefinger,
    getuserfingerdatabase,
    hapususer,
    simpanuploaduser,
    upload,
}
