import httpClient from './httpClient';

const simpansites = (params) => httpClient.post('/finger/simpan-sites', params);
const getsites = (params) => httpClient.get('/finger/get-sites', { params });
const hapussites = (params) => httpClient.post('/finger/del-sites', params);

export {
    simpansites,
    getsites,
    hapussites,
}
