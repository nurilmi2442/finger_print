import axios from 'axios';

const httpClient = axios.create({
    headers: {
        "Content-Type": "application/json",
        // anything you want to add to the headers
    }
});

// interceptor to catch errors
const errorInterceptor = error => {

    // check if it's a server error
    if (!error.response) {
      alert('Network/Server error');
      return Promise.reject(error);
    }

    // all the error responses
    switch(error.response.status) {
        case 400:
            console.error(error.response.status, error.message);
            alert('Nothing to display','Data Not Found');
            break;

        case 401: // authentication error, logout the user
            alert( 'Please login again', 'Session Expired');
            localStorage.removeItem('token');
            router.push('/auth');
            break;

        default:
            // console.error(error.response.status, error.message);
            alert('Server Error');
            

    }
    return Promise.reject(error);
}

// Interceptor for responses
const responseInterceptor = response => {
    switch(response.status) {
        case 200: 
            // yay!
            break;
        // any other cases
        default:
            // default case
    }

    return response;
}

httpClient.interceptors.response.use(responseInterceptor, errorInterceptor);

export default httpClient;