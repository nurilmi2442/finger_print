import httpClient from './httpClient';

const simpanusers = (params) => httpClient.post('/finger/simpan-users', params);
const getusers = (params) => httpClient.get('/finger/get-users', { params });
const hapususers = (params) => httpClient.post('/finger/del-users', params);

export {
    simpanusers,
    getusers,
    hapususers,
}
