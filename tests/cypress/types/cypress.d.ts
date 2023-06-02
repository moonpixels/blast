declare global {
  namespace Cypress {
    interface Cypress extends Cypress {
      /**
       * The Laravel Cypress helper.
       */
      Laravel: {
        routes: {
          [name: string]: LaravelRoute
        }

        route(name: string, parameters: Record<string, any>): string

        currentUser?: Record<string, any>
      }
    }
  }
}

export interface LaravelRoute {
  name: string
  domain: string | null
  action: string
  uri: string
  method: string[]
}
