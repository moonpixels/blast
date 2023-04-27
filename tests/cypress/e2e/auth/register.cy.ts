describe('Register', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    cy.visit({ route: 'register' })

    cy.get('[data-cy="register-form"]').as('registerForm').within(() => {
      cy.getFormInput('Full name').as('nameInput')
      cy.getFormInput('Email').as('emailInput')
      cy.getFormInput('Password').as('passwordInput')
      cy.get('[data-cy="submit-button"]').as('submitButton')
    })
  })

  it('should allow visitors to register for an account', () => {
    cy.get('@nameInput').type('John Doe')
    cy.get('@emailInput').type('john.doe@example.com')
    cy.get('@passwordInput').type('password')

    cy.get('@submitButton').click()

    cy.assertRedirect('dashboard')
  })

  it('should display registration errors', () => {
    // Required fields (browser validation)
    cy.get('@registerForm').within(() => {
      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 3)

      cy.get('@nameInput').type('John Doe')
      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 2)

      cy.get('@emailInput').type('john.doe@example.com')
      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)
    })

    // Required fields (server validation)
    cy.get('@registerForm').within(() => {
      cy.get('@nameInput').clear().invoke('removeAttr', 'required')
      cy.get('@emailInput').clear().invoke('removeAttr', 'required')
      cy.get('@passwordInput').clear().invoke('removeAttr', 'required')

      cy.get('@submitButton').click()

      cy.get('[data-cy="input-error-message"]').should('have.length', 3)
      cy.get('[data-cy="input-error-message"]').eq(0).should('contain', 'The name field is required.')
      cy.get('[data-cy="input-error-message"]').eq(1).should('contain', 'The email field is required.')
      cy.get('[data-cy="input-error-message"]').eq(2).should('contain', 'The password field is required.')
    })

    // Invalid email
    cy.get('@registerForm').within(() => {
      cy.get('@nameInput').type('John Doe')
      cy.get('@passwordInput').type('password')

      cy.get('@emailInput').type('invalid-email')
      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@emailInput').invoke('attr', 'type', 'text')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The email field must be a valid email address.')

      cy.get('@emailInput').clear().type('invalid-email@')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The email field must be a valid email address.')
    })

    // Invalid password
    cy.get('@registerForm').within(() => {
      cy.get('@nameInput').clear().type('John Doe')
      cy.get('@emailInput').clear().type('john.doe@example.com')

      cy.get('@passwordInput').clear().type('short')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The password must be at least 8 characters.')
    })
  })

  // TODO: Implement this test once the login page is implemented.
  it.skip('should have a link to the login page', () => {
    cy.get('[data-cy="login-link"]').should('have.attr', 'href')
      .and('contain', '/login')

    cy.get('[data-cy="login-link"]').click()

    cy.assertRedirect('login')
  })
})