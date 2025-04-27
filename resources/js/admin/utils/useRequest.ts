import { usePage } from '@inertiajs/vue3'
import axios, { type AxiosRequestConfig } from 'axios'
import type { ValidRouteName } from 'ziggy-js'

const instance = axios.create()

instance.interceptors.request.use((config) => {
    config.headers['X-CSRF-TOKEN'] = usePage().props.csrf_token
    return config
})

instance.interceptors.response.use(
    ({ data }) => {
        if (data.code == 0) {
            return data.response
        }
        return Promise.reject(data)
    },
    (err) => {
        console.log(err)
        return err
    },
)

export const useApiGet = (
    url: ValidRouteName,
    params?: AxiosRequestConfig['params'],
    config?: Omit<AxiosRequestConfig, 'params'>,
) => {
    return instance.get(route(url), {
        ...config,
        params,
    })
}
