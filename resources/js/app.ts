import './bootstrap'
import '../css/app.css'

import { createApp, DefineComponent, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import { i18nVue } from 'laravel-vue-i18n'

createInertiaApp({
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`,
        import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup ({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(i18nVue, {
                resolve: async (lang: string) => {
                    const langs = import.meta.glob('../../lang/*.json')
                    if (lang.includes('php_')) {
                        return await langs[`../../lang/${lang}.json`]()
                    }
                },
            })
            .mount(el)
    },
    progress: {
        color: '#8b5cf6',
    },
})