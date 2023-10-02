import httpClient from './httpClient';

const simpandatashift = (params) => httpClient.post('/finger/simpan-datashift', params);
const getdatashift = (params) => httpClient.get('/finger/get-datashift', { params });
const hapusdatashift = (params) => httpClient.post('/finger/del-datashift', params);

export {
    simpandatashift,
    getdatashift,
    hapusdatashift,
}
