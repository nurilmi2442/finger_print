import httpClient from './httpClient';

const getcreate= (params) => httpClient.get('/device/get-create', { params });
const upload= (params) => httpClient.post('/device/upload-device', params);

export {
    getcreate,
    upload

}
