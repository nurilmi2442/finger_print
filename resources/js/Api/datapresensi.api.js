import httpClient from './httpClient';

const getdatapresensi= (params) => httpClient.get('/mesin/get-datapresensi', { params });

export {
    getdatapresensi,
}
