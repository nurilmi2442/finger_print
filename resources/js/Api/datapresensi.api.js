import httpClient from './httpClient';

const getdatapresensi= (params) => httpClient.get('/mesin/get-datapresensi', { params });
const getfingerdatabase= (params) => httpClient.get('/mesin/get-datafingerdatabase', { params })

export {
    getdatapresensi,
    getfingerdatabase,
}
