import { createUser } from '../../support/functions'

describe('Delete account', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    createUser()

    cy.login({ attributes: { email: 'john.doe@example.com' } })
    cy.confirmPassword('password')
    cy.visit({ route: 'account-settings.show' })
  })

  it('should allow users to delete their account', () => {
    cy.get('[data-cy="delete-account-button"]').click()

    cy.get('[data-cy="delete-account-modal"]').within(() => {
      cy.get('[data-cy="delete-account-button"]').click()
    })

    cy.location('pathname').should('eq', '/')

    cy.visit({ route: 'login' })

    cy.get('[data-cy="login-form"]').within(() => {
      cy.getFormInput('Email').type('john.doe@example.com')
      cy.getFormInput('Password').type('password')
      cy.get('[data-cy="submit-button"]').click()

      cy.get('[data-cy="input-error-message"]').should('contain', 'These credentials do not match our records.')
    })
  })

  it('should not delete the account if the user cancels the deletion', () => {
    cy.get('[data-cy="delete-account-button"]').click()

    cy.get('[data-cy="delete-account-modal"]').within(() => {
      cy.get('[data-cy="cancel-button"]').click()
    })

    cy.location('pathname').should('eq', '/account/settings')
  })
})
