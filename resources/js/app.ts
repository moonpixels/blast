import './bootstrap'
import '../css/app.css'

import { createApp, DefineComponent, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'

const appName = window.document.getElementsByTagName('title')[0]?.innerText ||
    'Blast'

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`,
        import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup ({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) }).
            use(plugin).
            use(ZiggyVue).
            mount(el)
    },
    progress: {
        color: '#8b5cf6',
    },
})