import './bootstrap'
import 'tippy.js/animations/scale-subtle.css'
import 'v-calendar/style.css'
import '../css/app.css'

import { createApp, DefineComponent, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import { i18nVue } from 'laravel-vue-i18n'
import VueTippy from 'vue-tippy'
import VCalendar from 'v-calendar'

createInertiaApp({
  resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
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
      .use(VueTippy, {
        defaultProps: {
          theme: 'blast',
          duration: [300, 150],
          inertia: true,
          animation: 'scale-subtle',
        },
      })
      .use(VCalendar, {})
      .mount(el)
  },
  progress: {
    color: '#8b5cf6',
  },
})
