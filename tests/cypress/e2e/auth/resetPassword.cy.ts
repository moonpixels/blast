describe('Reset Password', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    cy.create({
      model: 'App\\Models\\User',
      attributes: {
        email: 'john.doe@example.com',
      },
    }).then((user) => {
      cy.php(`Illuminate\\Support\\Facades\\Password::createToken(App\\Models\\User::find(${user.id}))`)
        .then((token) => {
          cy.visit({
            route: 'password.reset',
            parameters: {
              token,
            },
            qs: {
              email: user.email,
            },
          })
        })
    })

    cy.get('[data-cy="reset-password-form"]').as('resetPasswordForm').within(() => {
      cy.getFormInput('Email').as('emailInput')
      cy.getFormInput('New password').as('passwordInput')
      cy.get('[data-cy="submit-button"]').as('submitButton')
    })
  })

  it('should allow users to reset their password', () => {
    cy.get('@resetPasswordForm').within(() => {
      cy.get('@emailInput').should('have.value', 'john.doe@example.com')
      cy.get('@passwordInput').type('new-password')
      cy.get('@submitButton').click()
    })

    cy.assertRedirect('login')

    cy.get('[data-cy="login-form"]').within(() => {
      cy.getFormInput('Email').type('john.doe@example.com')
      cy.getFormInput('Password').type('new-password')
      cy.get('[data-cy="submit-button"]').click()
    })

    cy.assertRedirect('dashboard')
  })

  it('should show an error if the password reset token is invalid', () => {
    cy.visit({
      route: 'password.reset',
      parameters: {
        token: 'invalid-token',
      },
      qs: {
        email: 'john.doe@example.com',
      },
    })

    cy.get('@resetPasswordForm').within(() => {
      cy.getFormInput('Email').should('have.value', 'john.doe@example.com')
      cy.getFormInput('New password').type('new-password')
      cy.get('@submitButton').click()

      cy.get('[data-cy="input-error-message"]').should('contain', 'The password reset token is invalid or has expired.')
    })
  })

  it('should show an error if the email is invalid', () => {
    // Missing email
    cy.get('@resetPasswordForm').within(() => {
      cy.get('@passwordInput').type('new-password')

      cy.get('@emailInput').clear()
      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@emailInput').clear().invoke('removeAttr', 'required')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The email field is required.')
    })

    // Invalid email
    cy.get('@resetPasswordForm').within(() => {
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

  it('should show an error if the password is invalid', () => {
    // Missing password
    cy.get('@resetPasswordForm').within(() => {
      cy.get('@passwordInput').clear()
      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@passwordInput').clear().invoke('removeAttr', 'required')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The password field is required.')
    })

    // Invalid password
    cy.get('@resetPasswordForm').within(() => {
      cy.get('@passwordInput').clear().type('short')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The password must be at least 8 characters.')
    })
  })
})