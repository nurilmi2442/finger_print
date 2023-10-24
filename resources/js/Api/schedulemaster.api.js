import httpClient from './httpClient';

const getschedulemaster= (params) => httpClient.get('/finger/get-schedulemaster', { params });
const hapusschedulemaster = (params) => httpClient.post('/finger/del-schedulemaster', params);
const getpegawai= (params) => httpClient.get('/finger/get-pegawai', {params});
const getworkingsch= (params) => httpClient.post('/finger/simpan-workingsch', params);


export {
    getschedulemaster,
    hapusschedulemaster,
    getpegawai,
    getworkingsch,
}
