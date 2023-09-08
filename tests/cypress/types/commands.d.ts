declare global {
  namespace Cypress {
    interface Chainable {
      login(attributes?: LoginAttributes): Chainable<any>

      currentUser(): Chainable<any>

      logout(): Chainable<any>

      csrfToken(): Chainable<any>

      refreshRoutes(): Chainable<any>

      create(request: CreateRequest): Chainable<any>

      visit(
        options: Partial<VisitOptions> & {
          route: string
          parameters?: Record<string, any>
        }
      ): Chainable<any>

      refreshDatabase(options?: object): Chainable<any>

      seed(seederClass: string): Chainable<any>

      artisan(command: string, parameters: Record<string, string>, options: Record<string, any>): Chainable<any>

      php(command: string): Chainable<any>
    }
  }
}

export interface LoginAttributes {
  attributes?: Record<string, any>
  state?: string[]
  load?: string[]
}

export interface CreateRequest {
  model: string
  count?: number
  attributes?: Record<string, any>
  state?: string[] | { [key: string]: any }
  load?: string[]
}
