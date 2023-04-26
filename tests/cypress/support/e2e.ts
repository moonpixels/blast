import './helpers/laravel'
import './commands'

before(() => {
  cy.task('activateCypressEnvFile', {}, { log: false })
  cy.artisan('config:clear', {}, { log: false })

  cy.refreshRoutes()
})

after(() => {
  cy.task('activateLocalEnvFile', {}, { log: false })
  cy.artisan('config:clear', {}, { log: false })
})
