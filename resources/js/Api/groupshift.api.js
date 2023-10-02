import httpClient from './httpClient';

const simpangroupshift= (params) => httpClient.post('/finger/simpan-groupshift', params);
const getgroupshift = (params) => httpClient.get('/finger/get-groupshift', { params });
const hapusgroupshift = (params) => httpClient.post('/finger/del-groupshift', params);

export {
    simpangroupshift,
    getgroupshift,
    hapusgroupshift,
}
