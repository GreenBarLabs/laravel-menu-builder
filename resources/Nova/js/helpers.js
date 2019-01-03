/**
 * Helpers for the nova vue components
**/

export function makeRandomKey(extra = '') {
    let key = "";
    let possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (let i = 0; i < 7; i++) {
        key += possible.charAt(Math.floor(Math.random() * possible.length));
    }

    return key + extra;
}
