describe('Confirm password', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    cy.create({
      model: 'App\\Models\\User',
      attributes: {
        email: 'john.doe@example.com',
      },
    })

    cy.login({ attributes: { email: 'john.doe@example.com' } })
    cy.visit({ route: 'password.confirm' })

    cy.get('[data-cy="confirm-password-form"]').as('confirmPasswordForm').within(() => {
      cy.getFormInput('Password').as('passwordInput')
      cy.get('[data-cy="submit-button"]').as('submitButton')
    })
  })

  it('should allow users to confirm their password', () => {
    cy.get('@confirmPasswordForm').within(() => {
      cy.get('@passwordInput').type('password')
      cy.get('@submitButton').click()
    })

    cy.assertRedirect('links')
  })

  it('should show an error if the password is invalid', () => {
    // Missing password
    cy.get('@confirmPasswordForm').within(() => {
      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@passwordInput').clear().invoke('removeAttr', 'required')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The provided password was incorrect.')
    })

    // Invalid password
    cy.get('@confirmPasswordForm').within(() => {
      cy.get('@passwordInput').type('wrong-password')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The provided password was incorrect.')
    })
  })
})