import {client} from "./request";
import {serverError} from "./notify";
import moment from "moment-timezone";
import SecureLS from "secure-ls";
import axios from "axios";

export const DEFAULT_STORAGE = new SecureLS({
    encodingType: 'base64',
    isCompression: false
});

export function setStorage(key, value) {
    DEFAULT_STORAGE.set(key, value);
}

export function getStorage(key) {
    return DEFAULT_STORAGE.get(key) || null;
}

export function removeStorage(key, value) {
    return DEFAULT_STORAGE.remove(key);
}

export function hasAuthUser() {
    let user = DEFAULT_STORAGE.get('auth');
    user = ((user && user !== '') ? JSON.parse(user) : {});

    if (!user)
        return false;

    return !!(user.access_token);
}

export function getAuthUser(defaultAction = true) {
    let user = DEFAULT_STORAGE.get('auth');
    user = ((user && user !== '') ? JSON.parse(user) : {});

    if (Object.keys(user).length <= 0) {
        if (!defaultAction)
            return {};

        removeStorage('auth');
        refresh();
        return {};
    }

    return user;
}

export function handleSyncRequestLoader({dispatch}, baseURL) {
    client.interceptors.request.use(
        config => {
            dispatch('addPendingRequests');
            config.baseURL = `${baseURL}/api`;
            return config;
        },
        error => {
            return Promise.reject(error);
        }
    );

    client.interceptors.response.use(
        ({data}) => {
            dispatch('removePendingRequests');
            return Promise.resolve(data);
        },
        error => {
            dispatch('removePendingRequests');
            return Promise.reject(error);
        }
    );
}

export function refresh() {
    window.location.reload();
}

export function handleServerError(errors) {
    if (errors && errors.status && errors.status === 500) {
        // console.log(errors.data.message);
        serverError();
        thisFunctionDoesNotExistAndWasCreatedWithTheOnlyPurposeOfStopJavascriptExecutionOfAllTypesIncludingCatchAndAnyArbitraryWeirdScenario();
    }
}

export function utcDateToLocalDate(date, toFormat = 'DD-MM-YYYY HH:mm A', fromFormat = 'YYYY-MM-DD HH:mm:ss') {
    if (!date) {
        return null;
    }

    const utcDate = moment.utc(date, fromFormat);
    return utcDate.clone().tz(moment.tz.guess()).format(toFormat);
}

export function dateToUtcDate(date, fromFormat = 'YYYY-MM-DD[T]HH:mm:ss', toFormat = 'YYYY-MM-DD[T]HH:mm:ss') {
    if (!date) {
        return null;
    }

    return moment(date, fromFormat).clone().tz(moment.tz.guess()).utc().format(toFormat);
}
