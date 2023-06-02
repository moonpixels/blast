import { createUser } from '../../support/functions'

describe('Register', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    createUser({ email: 'existing-user@example.com' })

    cy.visit({ route: 'register' })

    cy.get('[data-cy="register-form"]')
      .as('registerForm')
      .within(() => {
        cy.getFormInput('Full name').as('nameInput')
        cy.getFormInput('Email').as('emailInput')
        cy.getFormInput('Password').as('passwordInput')
        cy.get('[data-cy="submit-button"]').as('submitButton')
      })
  })

  it('should allow visitors to register for an account', () => {
    cy.get('@registerForm').within(() => {
      cy.get('@nameInput').type('John Doe')
      cy.get('@emailInput').type('john.doe@example.com')
      cy.get('@passwordInput').type('password')
      cy.get('@submitButton').click()
    })

    cy.assertRedirect('links')
  })

  it('should show an error if the name is invalid', () => {
    // Missing name
    cy.get('@registerForm').within(() => {
      cy.get('@emailInput').type('john.doe@example.com')
      cy.get('@passwordInput').type('password')

      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@nameInput').clear().invoke('removeAttr', 'required')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The name field is required.')
    })
  })

  it('should show an error if the email is invalid', () => {
    // Missing email
    cy.get('@registerForm').within(() => {
      cy.get('@nameInput').type('John Doe')
      cy.get('@passwordInput').type('password')

      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@emailInput').clear().invoke('removeAttr', 'required')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The email field is required.')
    })

    // Invalid email
    cy.get('@registerForm').within(() => {
      cy.get('@emailInput').type('invalid-email')
      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@emailInput').invoke('attr', 'type', 'text')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The email field must be a valid email address.')

      cy.get('@emailInput').clear().type('existing-user@example.com')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The email has already been taken.')
    })
  })

  it('should show an error if the password is invalid', () => {
    // Missing password
    cy.get('@registerForm').within(() => {
      cy.get('@nameInput').type('John Doe')
      cy.get('@emailInput').type('john.doe@example.com')

      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@passwordInput').clear().invoke('removeAttr', 'required')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The password field is required.')
    })

    // Invalid password
    cy.get('@registerForm').within(() => {
      cy.get('@passwordInput').clear().type('short')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The password must be at least 8 characters.')
    })
  })

  it('should have a link to the login page', () => {
    cy.get('[data-cy="login-link"]').should('have.attr', 'href').and('contain', '/login')

    cy.get('[data-cy="login-link"]').click()

    cy.assertRedirect('login')
  })
})
