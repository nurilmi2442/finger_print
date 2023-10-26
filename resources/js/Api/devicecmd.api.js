import httpClient from './httpClient';

const getdevice = (params) => httpClient.get('/device/get-device', { params });

export {
    getdevice,
}
