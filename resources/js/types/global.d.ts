import { PageProps as InertiaPageProps } from '@inertiajs/core'
import { AxiosInstance } from 'axios'
import ziggyRoute, { Config as ZiggyConfig } from 'ziggy-js'
import { PageProps as AppPageProps } from './'
import { trans, transChoice } from 'laravel-vue-i18n'

declare global {
  interface Window {
    axios: AxiosInstance
  }

  const route: typeof ziggyRoute
  const Ziggy: ZiggyConfig
  const $t: typeof trans
  const $tChoice: typeof transChoice
}

declare module 'vue' {
  interface ComponentCustomProperties {
    route: typeof ziggyRoute
    $t: typeof trans
    $tChoice: typeof transChoice
  }
}

declare module '@inertiajs/core' {
  interface PageProps extends InertiaPageProps, AppPageProps {}
}