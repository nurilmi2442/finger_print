import httpClient from './httpClient';

const simpandepartemen = (params) => httpClient.post('/finger/simpan-departemen', params);
const getdepartemen = (params) => httpClient.get('/finger/get-departemen', { params });
const hapusdepartemen = (params) => httpClient.post('/finger/del-departemen', params);

export {
    simpandepartemen,
    getdepartemen,
    hapusdepartemen,
}
