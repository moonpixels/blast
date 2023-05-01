describe('Login', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    cy.create({
      model: 'App\\Models\\User',
      attributes: {
        email: 'john.doe@example.com',
      },
    })

    cy.visit({ route: 'login' })

    cy.get('[data-cy="login-form"]').as('loginForm').within(() => {
      cy.getFormInput('Email').as('emailInput')
      cy.getFormInput('Password').as('passwordInput')
      cy.getFormInput('Remember me').as('rememberMeInput')
      cy.get('[data-cy="submit-button"]').as('submitButton')
    })
  })

  it('should allow users to login', () => {
    cy.get('@loginForm').within(() => {
      cy.get('@emailInput').type('john.doe@example.com')
      cy.get('@passwordInput').type('password')
      cy.get('@rememberMeInput').check()
      cy.get('@submitButton').click()
    })

    cy.assertRedirect('dashboard')
  })

  it('should show an error if the email is invalid', () => {
    // Missing email
    cy.get('@loginForm').within(() => {
      cy.get('@passwordInput').type('password')

      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@emailInput').clear().invoke('removeAttr', 'required')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The email field is required.')
    })

    // Invalid email
    cy.get('@loginForm').within(() => {
      cy.get('@emailInput').type('invalid-email')
      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@emailInput').invoke('attr', 'type', 'text')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'These credentials do not match our records.')

      cy.get('@emailInput').clear().type('not-registered@example.com')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'These credentials do not match our records.')
    })
  })

  it('should show an error if the password is invalid', () => {
    // Missing password
    cy.get('@loginForm').within(() => {
      cy.get('@emailInput').type('john.doe@example.com')

      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@passwordInput').clear().invoke('removeAttr', 'required')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The password field is required.')
    })

    // Invalid password
    cy.get('@loginForm').within(() => {
      cy.get('@passwordInput').clear().type('invalid-password')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'These credentials do not match our records.')
    })
  })

  it('should have a link to the registration page', () => {
    cy.get('[data-cy="register-link"]').should('have.attr', 'href')
      .and('contain', '/register')

    cy.get('[data-cy="register-link"]').click()

    cy.assertRedirect('register')
  })

  it('should have a link to the password reset page', () => {
    cy.get('[data-cy="forgot-password-link"]').should('have.attr', 'href')
      .and('contain', '/forgot-password')

    cy.get('[data-cy="forgot-password-link"]').click()

    cy.assertRedirect('forgot-password')
  })
})