declare global {
  namespace Cypress {
    interface Chainable<Subject> {
      waitForSpecToFinish(): Chainable<any>

      assertRedirect(path: string): Chainable<any>
    }
  }
}

/**
 * Wait for the spec to finish running.
 */
export function waitForSpecToFinish() {
  cy.get('.passed > .num').should('contain', '--')
  cy.get('.failed > .num').should('contain', '--')
  cy.contains('Your tests are loading...').should('not.exist')
  cy.get('[aria-label="Rerun all tests"]', { timeout: 30000 })
}

Cypress.Commands.add('waitForSpecToFinish', waitForSpecToFinish)

/**
 * Assert that the current URL matches the given path.
 */
Cypress.Commands.add('assertRedirect', (path: string) => {
  cy.location('pathname').should('eq', `/${path}`.replace(/^\/\//, '/'))
})
