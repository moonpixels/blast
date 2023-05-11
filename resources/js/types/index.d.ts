export interface User {
  id: number
  name: string
  email: string
  two_factor_enabled: boolean
  initials: string
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> =
  T & {
  auth: {
    user: User
  }
}