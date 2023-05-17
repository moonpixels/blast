import { createUser } from '../../support/functions'

describe('Forgot password', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    createUser()

    cy.visit({ route: 'password.request' })

    cy.get('[data-cy="forgot-password-form"]').as('forgotPasswordForm').within(() => {
      cy.getFormInput('Email').as('emailInput')
      cy.get('[data-cy="submit-button"]').as('submitButton')
    })
  })

  it('should allow users to request a password reset', () => {
    cy.get('@forgotPasswordForm').within(() => {
      cy.get('@emailInput').type('john.doe@example.com')
      cy.get('@submitButton').click()
    })

    cy.get('@forgotPasswordForm').should('not.exist')
    cy.get('[data-cy="success-message"]').should('be.visible')
  })

  it('should show an error if the email is invalid', () => {
    // Missing email
    cy.get('@forgotPasswordForm').within(() => {
      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@emailInput').clear().invoke('removeAttr', 'required')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The email field is required.')
    })

    // Invalid email
    cy.get('@forgotPasswordForm').within(() => {
      cy.get('@emailInput').type('invalid-email')
      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@emailInput').invoke('attr', 'type', 'text')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The email field must be a valid email address.')

      cy.get('@emailInput').clear().type('not-registered@example.com')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'We can\'t find a user with that email address.')
    })
  })
})