import storage from './storage'

/**
 * 获取token
 * @param type - token类型，'access_token' 或 'refresh_token'
 * @returns
 */
export function getToken(type: 'access_token' | 'refresh_token'): null | string {
    return storage.get(type);
}

/**
 * 设置token
 * @param token - token值
 * @param type - token类型，'access_token' 或 'refresh_token'
 * @returns
 */
export function setToken(token: string, type: 'access_token' | 'refresh_token'): void {
    storage.set(type, token, 7 * 24 * 60 * 60);
}

/**
 * 移除token
 * @param type - token类型，'access_token' 或 'refresh_token'
 * @returns
 */
export function removeToken(type: 'access_token' | 'refresh_token'): void {
    storage.remove(type);
}

