import httpClient from './httpClient';

const simpansites = (params) => httpClient.post('/device/simpan-sites', params);
const getsites = (params) => httpClient.get('/device/get-sites', { params });
const hapussites = (params) => httpClient.post('/device/del-sites', params);

export {
    simpansites,
    getsites,
    hapussites,
}
