import { createUser } from '../../support/functions'

describe('Password', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    createUser()

    cy.login({ attributes: { email: 'john.doe@example.com' } })
    cy.confirmPassword('password')
    cy.visit({ route: 'account-settings' })

    cy.get('[data-cy="password-form"]').as('passwordForm').within(() => {
      cy.getFormInput('Current password').as('currentPasswordInput')
      cy.getFormInput('New password').as('newPasswordInput')
      cy.get('[data-cy="submit-button"]').as('submitButton')
    })
  })

  it('should allow users to update their password', () => {
    cy.get('@passwordForm').within(() => {
      cy.get('@currentPasswordInput').type('password')
      cy.get('@newPasswordInput').type('new-password')
      cy.get('@submitButton').click()
    })

    cy.get('[data-cy="success-notification"]').should('contain', 'Password updated')

    cy.logout()

    cy.visit({ route: 'login' })

    cy.get('[data-cy="login-form"]').within(() => {
      cy.getFormInput('Email').type('john.doe@example.com')
      cy.getFormInput('Password').type('new-password')
      cy.get('[data-cy="submit-button"]').click()
    })

    cy.assertRedirect('links')
  })

  it('should show an error if the current password is invalid', () => {
    // Missing current password
    cy.get('@passwordForm').within(() => {
      cy.get('@newPasswordInput').type('new-password')
      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@currentPasswordInput').clear().invoke('removeAttr', 'required')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The current password field is required.')

    })

    // Invalid current password
    cy.get('@passwordForm').within(() => {
      cy.get('@currentPasswordInput').type('wrong-password')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The provided password does not match your current password.')
    })
  })

  it('should show an error if the new password is invalid', () => {
    // Missing new password
    cy.get('@passwordForm').within(() => {
      cy.get('@currentPasswordInput').type('password')
      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@newPasswordInput').clear().invoke('removeAttr', 'required')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The password field is required.')
    })

    // Invalid new password
    cy.get('@passwordForm').within(() => {
      cy.get('@newPasswordInput').type('short')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The password must be at least 8 characters.')
    })
  })
})