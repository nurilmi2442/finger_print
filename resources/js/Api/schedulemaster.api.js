import httpClient from './httpClient';

const getschedulemaster= (params) => httpClient.get('/finger/get-schedulemaster', { params });
const hapusschedulemaster = (params) => httpClient.post('/finger/del-schedulemaster', params);

export {
    getschedulemaster,
    hapusschedulemaster,
}
