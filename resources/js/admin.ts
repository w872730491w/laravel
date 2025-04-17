import '../css/admin.css'

import { createInertiaApp } from '@inertiajs/vue3'
import type { DefineComponent } from 'vue'
import { createApp, h } from 'vue'
import { ZiggyVue } from 'ziggy-js'
import Layout from './components/admin/layout/AppLayout.vue'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        const pages = import.meta.glob<DefineComponent>('./pages/admin/**/*.vue', { eager: false })
        let page = pages[`./pages/admin/${name}.vue`]().then((page) => {
            if (page.default.layout !== false) {
                page.default.layout = page.default.layout || Layout
            }
            return page
        })
        return page
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el)
        useTheme()
    },
    progress: {
        color: '#4B5563',
    },
})
