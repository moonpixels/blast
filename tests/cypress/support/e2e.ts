import './helpers/laravel'
import './commands'

before(() => {
  cy.artisan('config:clear', {}, { log: false })

  cy.refreshRoutes()
})

after(() => {
  cy.artisan('config:clear', {}, { log: false })
})
