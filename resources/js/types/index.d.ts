import { PageProps as InertiaPageProps } from '@inertiajs/core'

export interface User {
  id: number
  name: string
  email: string
  two_factor_enabled: boolean
  initials: string
}

export interface FlashMessage {
  title: string
  message: string
}

export type PageProps<T extends InertiaPageProps = InertiaPageProps> = T & {
  user: User
  flash: {
    success?: FlashMessage
    error?: FlashMessage
  }
}