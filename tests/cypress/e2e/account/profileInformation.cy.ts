import { createUser } from '../../support/functions'

describe('Profile information', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    createUser()

    cy.login({ attributes: { email: 'john.doe@example.com' } })
    cy.confirmPassword('password')
    cy.visit({ route: 'account-settings.show' })

    cy.get('[data-cy="profile-information-form"]').as('profileInformationForm').within(() => {
      cy.getFormInput('Full name').as('nameInput')
      cy.getFormInput('Email').as('emailInput')
      cy.get('[data-cy="submit-button"]').as('submitButton')
    })
  })

  it('should allow users to update their profile information', () => {
    cy.get('@profileInformationForm').within(() => {
      cy.get('@nameInput').clear().type('New name')
      cy.get('@emailInput').clear().type('new.email@example.com')
      cy.get('@submitButton').click()
    })

    cy.get('[data-cy="success-notification"]').should('contain', 'Profile updated')
  })

  it('should show an error if the name is invalid', () => {
    // Missing name
    cy.get('@profileInformationForm').within(() => {
      cy.get('@nameInput').clear()
      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@nameInput').clear().invoke('removeAttr', 'required')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The name field is required.')
    })
  })

  it('should show an error if the email is invalid', () => {
    // Missing email
    cy.get('@profileInformationForm').within(() => {
      cy.get('@emailInput').clear()
      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@emailInput').clear().invoke('removeAttr', 'required')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The email field is required.')
    })

    // Invalid email
    cy.get('@profileInformationForm').within(() => {
      cy.get('@emailInput').type('invalid-email')
      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@emailInput').invoke('attr', 'type', 'text')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The email field must be a valid email address.')

      createUser({ email: 'existing-user@example.com' })
      cy.get('@emailInput').clear().type('existing-user@example.com')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The email has already been taken.')
    })
  })
})