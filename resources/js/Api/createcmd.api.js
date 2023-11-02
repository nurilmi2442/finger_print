import httpClient from './httpClient';

const getcreate= (params) => httpClient.get('/device/get-create', { params });

export {
    getcreate
}
