import './helpers/laravel'
import './commands'

before(() => {
  cy.refreshRoutes()
})
