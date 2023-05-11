declare global {
  namespace Cypress {
    interface Chainable<Subject> {
      waitForSpecToFinish(): Chainable<any>

      assertRedirect(path: string): Chainable<any>

      getFormInput(label: string): Chainable<any>

      confirmPassword(password: string): Chainable<any>
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
Cypress.Commands.add('assertRedirect', (path) => {
  cy.location('pathname').should('eq', `/${path}`.replace(/^\/\//, '/'))
})

/**
 * Get the form field with the given label.
 */
Cypress.Commands.add('getFormInput', (label) => {
  return cy.contains('label', label).then(($label) => {
    const id = $label.prop('for')
    return cy.get(`#${id}`)
  })
})

/**
 * Go to the password confirmation page and confirm the password.
 */
Cypress.Commands.add('confirmPassword', (password: string) => {
  cy.visit({ route: 'password.confirm' })

  cy.get('[data-cy="confirm-password-form"]').within(() => {
    cy.getFormInput('Password').type(password)
    cy.get('[data-cy="submit-button"]').click()
  })

  cy.get('[data-cy="confirm-password-form"]').should('not.exist')
})
