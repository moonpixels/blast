import { PageProps as InertiaPageProps } from '@inertiajs/core'
import { User } from '@/types/models'

export type FlashMessage = {
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
