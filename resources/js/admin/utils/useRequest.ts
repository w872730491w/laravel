import { usePage } from '@inertiajs/vue3'
import axios, { AxiosError, type AxiosRequestConfig } from 'axios'
import { toast } from 'vue-sonner'
import type { ValidRouteName } from 'ziggy-js'

const instance = axios.create()

instance.interceptors.request.use((config) => {
    config.headers['X-CSRF-TOKEN'] = usePage().props.csrf_token
    return config
})

instance.interceptors.response.use(
    ({ data, config }) => {
        if (data.code == 0) {
            config.showMessage && data.message && toast.success(data.message, {
                position: 'top-center'
            })
            return data.response
        }
        if (data.code === 422) {
            toast.error(data.message, {
                position: 'top-center',
            })
        }
        return Promise.reject(data)
    },
    (err) => {
        return Promise.reject(
            err instanceof AxiosError
                ? err.response?.data
                : {
                      code: 1,
                      message: err.message,
                      data: null,
                  },
        )
    },
)

export const useApiGet = <T = any>(
    url: ValidRouteName,
    params?: AxiosRequestConfig['params'],
    config?: Omit<AxiosRequestConfig, 'params'>,
) => {
    return instance.get<T, T>(route(url), {
        ...config,
        params,
    })
}

export const useApiPost = <T = any>(
    url: ValidRouteName,
    data?: AxiosRequestConfig['data'],
    config?: Omit<AxiosRequestConfig, 'data'>,
) => {
    return instance.post<T, T>(route(url), data, config)
}

export const useApi = instance
