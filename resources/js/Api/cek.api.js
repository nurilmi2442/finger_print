import httpClient from './httpClient';

// you can pass arguments to use as request parameters/data
const getCekLpbg = (params) => httpClient.get('/cek/cek-by-lpbg', { params });
const getCekSttp = (params) => httpClient.get('/cek/cek-by-sttp', { params });

export {
    getCekLpbg,
    getCekSttp
}