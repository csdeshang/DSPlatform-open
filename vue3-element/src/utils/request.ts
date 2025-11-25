import axios from 'axios'
import type { AxiosInstance, InternalAxiosRequestConfig, AxiosResponse, AxiosRequestConfig, AxiosError } from 'axios'
import { getToken, setToken } from './auth'
import { isUrl } from './util'
import { ElMessage } from 'element-plus'
import type { MessageParams } from 'element-plus'
import storage from './storage'
import useUserInfoStore from '@/stores/modules/userInfo'
import { refreshToken } from '@/api/login'

// 定义API基础URL常量
export const API_BASE_URLS = {
    ADMIN: 'adminapi',     // 后台接口
    STORE: 'storeapi',     // 店铺接口
    MERCHANT: 'merchantapi', // 商户接口
    USER: 'api',           // 用户接口
}

// 定义请求配置接口
export interface RequestConfig extends AxiosRequestConfig {
    showErrorMessage?: boolean   // 是否显示错误消息
    showSuccessMessage?: boolean // 是否显示成功消息
}

// 定义内部请求配置接口
interface InternalRequestConfig extends InternalAxiosRequestConfig {
    showErrorMessage?: boolean
    showSuccessMessage?: boolean
}

// 定义API响应接口
export interface ApiResponse<T = any> {
    code: number
    message: string
    data: T
}

// 定义请求响应接口
interface RequestResponse extends AxiosResponse {
    config: InternalRequestConfig
}

/**
 * HTTP请求类
 * 封装axios，处理请求拦截、响应拦截、错误处理、token刷新等功能
 */
class Request {
    private instance: AxiosInstance
    private messageCache: Map<string, { timestamp: number }>
    private isRefreshing: boolean = false
    private refreshSubscribers: Array<(token: string) => void> = []

    constructor() {
        // 创建axios实例
        this.instance = axios.create({
            baseURL: this.normalizeBaseUrl(import.meta.env.VITE_APP_BASE_URL),
            timeout: 30000, // 默认30秒超时
            headers: {
                'Content-Type': 'application/json',
            }
        })

        // 初始化消息缓存
        this.messageCache = new Map()

        // 注册请求拦截器
        this.registerRequestInterceptor()

        // 注册响应拦截器
        this.registerResponseInterceptor()
    }

    /**
     * 规范化基础URL
     * @param url 基础URL
     * @returns 规范化后的URL
     */
    private normalizeBaseUrl(url: string): string {
        return url.endsWith('/') ? url : `${url}/`
    }

    /**
     * 注册请求拦截器
     */
    private registerRequestInterceptor(): void {
        this.instance.interceptors.request.use(
            (config: InternalRequestConfig) => {
                // 添加访问令牌
                const accessToken = getToken('access_token')
                if (accessToken) {
                    config.headers['access-token'] = accessToken
                }

                // 管理店铺ID
                const storeId = storage.get('manage_store_id')
                if (storeId) {
                    config.headers['manage-store-id'] = storeId
                }

                return config
            },
            (error: AxiosError) => {
                console.error('请求拦截器错误:', error)
                return Promise.reject(error)
            }
        )
    }

    /**
     * 注册响应拦截器
     */
    private registerResponseInterceptor(): void {
        this.instance.interceptors.response.use(
            (response: RequestResponse) => this.handleSuccessResponse(response),
            (error: AxiosError) => this.handleErrorResponse(error)
        )
    }

    /**
     * 处理成功响应
     * @param response 响应对象
     * @returns 处理后的响应数据
     */
    private handleSuccessResponse(response: RequestResponse): any {
        // 如果是二进制数据，直接返回
        if (response.request.responseType === 'blob') {
            return response.data
        }

        const res = response.data as ApiResponse

        // 业务逻辑错误处理
        if (res.code !== 10000) {
            if (response.config.showErrorMessage !== false) {
                this.showMessage({
                    message: res.message,
                    type: 'error',
                    dangerouslyUseHTMLString: true,
                    duration: 5000
                })
            }
            return res
        }

        // 显示成功消息
        if (response.config.showSuccessMessage) {
            this.showMessage({
                message: res.message,
                type: 'success'
            })
        }

        return res
    }

    /**
     * 处理错误响应
     * @param error 错误对象
     * @returns Promise.reject
     */
    private async handleErrorResponse(error: AxiosError<any>): Promise<any> {
        // 如果有响应，处理HTTP错误
        if (error.response) {
            return this.handleHttpError(error)
        } else {
            // 否则处理网络错误
            this.handleNetworkError(error)
            return Promise.reject(error)
        }
    }

    /**
     * 处理HTTP错误
     * @param error 错误对象
     * @returns Promise.reject或刷新token后的重试
     */
    private async handleHttpError(error: AxiosError): Promise<any> {
        const status = error.response?.status
        const config = error.config as InternalRequestConfig

        // 处理401错误（未授权）
        if (status === 401) {
            return this.handleTokenRefresh(error)
        }

        // 处理403错误（拒绝访问）
        if (status === 403) {
            const errorMessage = '权限不足，请重新登录'
            this.showMessage({
                message: errorMessage,
                type: 'error',
                duration: 3000
            })
            // 登出用户
            useUserInfoStore().logout()
        } else {
            // 处理其他HTTP错误
            const errorMessage = this.getHttpErrorMessage(status)
            this.showMessage({
                message: errorMessage,
                type: 'error',
                duration: 5000,
                dangerouslyUseHTMLString: true
            })
        }

        return Promise.reject(error)
    }

    /**
     * 获取HTTP错误消息
     * @param status HTTP状态码
     * @returns 错误消息
     */
    private getHttpErrorMessage(status?: number): string {
        switch (status) {
            case 400: return '请求错误（400）'
            case 401: return '未授权，请重新登录（401）'
            case 403: return '拒绝访问（403）'
            case 404: return '请求的资源不存在（404）'
            case 408: return '请求超时（408）'
            case 500: return '服务器内部错误（500）'
            case 501: return '服务未实现（501）'
            case 502: return '网关错误（502）'
            case 503: return '服务不可用（503）'
            case 504: return '网关超时（504）'
            case 505: return 'HTTP版本不受支持（505）'
            default: return `请求失败，状态码：${status || '未知'}`
        }
    }

    /**
     * 处理网络错误
     * @param error 错误对象
     */
    private handleNetworkError(error: AxiosError): void {
        let errorMessage = '网络连接错误'

        if (error.message.includes('timeout')) {
            errorMessage = '请求超时，请检查网络连接'
        } else if (error.code === 'ERR_NETWORK') {
            const config = error.config
            if (config) {
                const baseURL = isUrl(config.baseURL as string)
                    ? config.baseURL
                    : `${location.origin}${config.baseURL}`
                errorMessage = `无法连接到服务器：${baseURL}`
            }
        } else if (error.message.includes('Network Error')) {
            errorMessage = '网络错误，请检查您的互联网连接'
        }

        this.showMessage({
            message: errorMessage,
            type: 'error',
            duration: 5000,
            dangerouslyUseHTMLString: true
        })
    }

    /**
     * 处理Token刷新
     * @param error 错误对象
     * @returns 刷新token后的重试请求
     */
    private async handleTokenRefresh(error: AxiosError): Promise<any> {
        const config = error.config as InternalRequestConfig

        // 如果已经在刷新token，则将请求添加到订阅者列表
        if (this.isRefreshing) {
            return new Promise((resolve) => {
                this.refreshSubscribers.push((token: string) => {
                    if (config) {
                        config.headers['access-token'] = token
                        resolve(this.instance(config))
                    }
                })
            })
        }

        this.isRefreshing = true

        try {
            // 获取刷新token
            const refreshTokenValue = getToken('refresh_token')
            if (!refreshTokenValue) {
                throw new Error('刷新令牌不存在')
            }

            // 调用刷新token API
            const response = await refreshToken({ refresh_token: refreshTokenValue })

            if (response && response.data.access_token) {
                // 更新token
                const newAccessToken = response.data.access_token
                const newRefreshToken = response.data.refresh_token

                setToken(newAccessToken, 'access_token')
                setToken(newRefreshToken, 'refresh_token')

                // 通知所有订阅者
                this.refreshSubscribers.forEach(callback => callback(newAccessToken))
                this.refreshSubscribers = []

                // 重试原始请求
                if (config) {
                    config.headers['access-token'] = newAccessToken
                    return this.instance(config)
                }
            } else {
                throw new Error('刷新令牌失败')
            }
        } catch (refreshError) {
            console.error('刷新令牌失败:', refreshError)
            // 登出用户
            useUserInfoStore().logout()
            return Promise.reject(refreshError)
        } finally {
            this.isRefreshing = false
        }
    }

    /**
     * 显示消息
     * @param options 消息选项
     */
    private showMessage(options: MessageParams): void {
        const cacheKey = options.message as string
        const cachedMessage = this.messageCache.get(cacheKey)
        const now = Date.now()

        // 防止短时间内显示相同的消息
        if (!cachedMessage || now - cachedMessage.timestamp > 5000) {
            this.messageCache.set(cacheKey, { timestamp: now })
            ElMessage(options)
        }
    }

    /**
     * 发送GET请求
     * @param url 请求URL
     * @param config 请求配置
     * @returns Promise
     */
    public get<T = any>(url: string, config?: RequestConfig): Promise<ApiResponse<T>> {
        return this.instance.get(url, config)
    }

    /**
     * 发送POST请求
     * @param url 请求URL
     * @param data 请求数据
     * @param config 请求配置
     * @returns Promise
     */
    public post<T = any, D = any>(url: string, data?: D, config?: RequestConfig): Promise<ApiResponse<T>> {
        return this.instance.post(url, data, config)
    }

    /**
     * 发送PUT请求
     * @param url 请求URL
     * @param data 请求数据
     * @param config 请求配置
     * @returns Promise
     */
    public put<T = any, D = any>(url: string, data?: D, config?: RequestConfig): Promise<ApiResponse<T>> {
        return this.instance.put(url, data, config)
    }

    /**
     * 发送DELETE请求
     * @param url 请求URL
     * @param config 请求配置
     * @returns Promise
     */
    public delete<T = any>(url: string, config?: RequestConfig): Promise<ApiResponse<T>> {
        return this.instance.delete(url, config)
    }

    /**
     * 发送PATCH请求
     * @param url 请求URL
     * @param data 请求数据
     * @param config 请求配置
     * @returns Promise
     */
    public patch<T = any, D = any>(url: string, data?: D, config?: RequestConfig): Promise<ApiResponse<T>> {
        return this.instance.patch(url, data, config)
    }

    /**
     * 上传文件
     * @param url 请求URL
     * @param file 文件对象
     * @param data 附加数据
     * @param config 请求配置
     * @returns Promise
     */
    public upload<T = any>(url: string, file: File, data?: Record<string, any>, config?: RequestConfig): Promise<ApiResponse<T>> {
        const formData = new FormData()
        formData.append('file', file)

        // 添加其他数据
        if (data) {
            Object.entries(data).forEach(([key, value]) => {
                formData.append(key, value)
            })
        }

        return this.instance.post(url, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            ...config
        })
    }
}

// 导出请求实例
export default new Request()
