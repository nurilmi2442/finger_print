import httpClient from './httpClient';

const getlog = (params) => httpClient.get('/device/get-log', { params });

export {
    getlog,
}
