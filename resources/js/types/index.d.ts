import { PageProps as InertiaPageProps } from '@inertiajs/core'
import { CurrentUser } from '@/types/models'

export interface FlashMessage {
  title: string
  message: string
}

export type PageProps<T extends InertiaPageProps = InertiaPageProps> = T & {
  user: CurrentUser
  flash: {
    success?: FlashMessage
    error?: FlashMessage
  }
}
