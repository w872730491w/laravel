import type { Page } from '@inertiajs/core'
import type { SharedData } from './index'

declare global {
    interface Window {
        MonacoEnvironment: {
            getWorker(moduleId: string, label: string): Worker
        }
    }
}

declare module '@inertiajs/vue3' {
    export function usePage(): Page<SharedData>
}

declare module 'axios' {
    interface InternalAxiosRequestConfig {
        showMessage?: boolean
    }
    interface AxiosRequestConfig {
        showMessage?: boolean
    }
}

export {}
