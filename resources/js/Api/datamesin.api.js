import httpClient from './httpClient';

const simpandatamesin = (params) => httpClient.post('/finger/simpan-datamesin', params);
const getdatamesin = (params) => httpClient.get('/finger/get-datamesin', { params });
const hapusdatamesin = (params) => httpClient.post('/finger/del-datamesin', params);

export {
    simpandatamesin,
    getdatamesin,
    hapusdatamesin,
}
