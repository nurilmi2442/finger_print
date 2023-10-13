import httpClient from './httpClient';

const simpandatamesin = (params) => httpClient.post('/device/simpan-datamesin', params);
const getdatamesin = (params) => httpClient.get('/device/get-datamesin', { params });
const hapusdatamesin = (params) => httpClient.post('/device/del-datamesin', params);

export {
    simpandatamesin,
    getdatamesin,
    hapusdatamesin,
}
