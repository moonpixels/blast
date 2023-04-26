import { PageProps as InertiaPageProps } from '@inertiajs/core'
import { AxiosInstance } from 'axios'
import ziggyRoute, { Config as ZiggyConfig } from 'ziggy-js'
import { PageProps as AppPageProps } from './'
import { trans } from 'laravel-vue-i18n'

declare global {
  interface Window {
    axios: AxiosInstance
  }

  const route: typeof ziggyRoute
  const Ziggy: ZiggyConfig
  const defineOptions: typeof import('unplugin-vue-define-options/macros-global').defineOptions
  const $t: typeof trans
}

declare module 'vue' {
  interface ComponentCustomProperties {
    route: typeof ziggyRoute
    defineOptions: typeof import('unplugin-vue-define-options/macros-global').defineOptions
    $t: typeof trans
  }
}

declare module '@inertiajs/core' {
  interface PageProps extends InertiaPageProps, AppPageProps {}
}