import axios from 'axios'
import {getAuthUser, hasAuthUser, refresh, removeStorage} from "./utils";
import {sessionExpires} from "./notify";

/**
 * Create an Axios Client with defaults
 */

let axiosInstance = axios.create();

axiosInstance.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axiosInstance.defaults.headers.common['Content-Type'] = 'application/json';

if(hasAuthUser() && getAuthUser()) {
    const user = getAuthUser();

    axiosInstance.defaults.headers.common['Authorization'] = `Bearer ${user.access_token}`;
}

export const client = axiosInstance;

/**
 * Request Wrapper with default success/error actions
 */
export const request = function (options) {
    const onSuccess = function (response) {
        // console.debug('Request Successful!', response);
        return response;
    }

    const onError = function (error) {
        // console.error('Request Failed:', error.config);

        if (error.response) {
            // Request was made but server responded with something
            // other than 2xx
            // console.error('Status:', error.response.status);
            // console.error('Data:', error.response.data);
            // console.error('Headers:', error.response.headers);
            if(error.response.status === 401) {
                sessionExpires();
                setTimeout(() => {
                    removeStorage('auth');
                    refresh();
                }, 2000);

                return false;
            }
        } else {
            // Something else happened while setting up the request
            // triggered the error
            // console.error('Error Message:', error.message);
        }

        return Promise.reject(error.response || error.message);
    }

    return axiosInstance(options)
        .then(onSuccess)
        .catch(onError);
}
