import { PageProps as InertiaPageProps } from '@inertiajs/core'

export interface Team {
  id: string
  owner_id: string
  name: string
  personal_team: boolean
  created_at: string
  updated_at: string
}

export interface User {
  id: string
  name: string
  email: string
  current_team_id: string
  initials: string
  two_factor_enabled: boolean
  teams: Team[]
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
