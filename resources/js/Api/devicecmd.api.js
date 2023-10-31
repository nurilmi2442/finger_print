import httpClient from './httpClient';

const getdevice = (params) => httpClient.get('/device/get-device', { params });
const hapusdevice = (params) => httpClient.post('/device/del-device', params);

export {
    getdevice,
    hapusdevice,
}
