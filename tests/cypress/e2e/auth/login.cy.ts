import { getOtpCodeForUser } from '../../support/functions'
import { User } from '@/types'

describe('Login', () => {
  let tfaUser: User

  beforeEach(() => {
    cy.refreshDatabase()

    cy.create({
      model: 'App\\Models\\User',
      attributes: {
        email: 'john.doe@example.com',
      },
    })

    cy.create({
      model: 'App\\Models\\User',
      attributes: {
        email: 'john.doe+tfa@example.com',
      },
      state: ['withTwoFactorAuthentication'],
    }).then((model) => {
      tfaUser = model
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

    cy.assertRedirect('links')
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

  it('should allow users to login with two-factor authentication', () => {
    cy.get('@loginForm').within(() => {
      cy.get('@emailInput').type('john.doe+tfa@example.com')
      cy.get('@passwordInput').type('password')
      cy.get('@submitButton').click()
    })

    cy.assertRedirect('two-factor-challenge')

    cy.get('[data-cy="2fa-challenge-form"]').within(() => {
      getOtpCodeForUser(tfaUser).then((otpCode) => {
        cy.getFormInput('Two-factor code').type(otpCode)
      })
      cy.get('[data-cy="submit-button"]').click()
    })

    cy.assertRedirect('links')
  })

  it('should allow users to login with two-factor recovery codes', () => {
    cy.get('@loginForm').within(() => {
      cy.get('@emailInput').type('john.doe+tfa@example.com')
      cy.get('@passwordInput').type('password')
      cy.get('@submitButton').click()
    })

    cy.assertRedirect('two-factor-challenge')

    cy.get('[data-cy="switch-mode-button"]').click()

    cy.get('[data-cy="2fa-recovery-form"]').within(() => {
      cy.getFormInput('Recovery code').type('recovery-code')
      cy.get('[data-cy="submit-button"]').click()
    })

    cy.assertRedirect('links')
  })

  it('should show an error if the two-factor code is invalid', () => {
    cy.get('@loginForm').within(() => {
      cy.get('@emailInput').type('john.doe+tfa@example.com')
      cy.get('@passwordInput').type('password')
      cy.get('@submitButton').click()
    })

    cy.assertRedirect('two-factor-challenge')

    // Missing two-factor code
    cy.get('[data-cy="2fa-challenge-form"]').within(() => {
      cy.get('[data-cy="submit-button"]').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.getFormInput('Two-factor code').clear().invoke('removeAttr', 'required')
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The provided two factor authentication code was invalid.')
    })

    // Invalid two-factor code
    cy.get('[data-cy="2fa-challenge-form"]').within(() => {
      cy.getFormInput('Two-factor code').type('invalid-code')
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The provided two factor authentication code was invalid.')
    })
  })

  it('should show an error if the two-factor recovery code is invalid', () => {
    cy.get('@loginForm').within(() => {
      cy.get('@emailInput').type('john.doe+tfa@example.com')
      cy.get('@passwordInput').type('password')
      cy.get('@submitButton').click()
    })

    cy.assertRedirect('two-factor-challenge')

    cy.get('[data-cy="switch-mode-button"]').click()

    // Missing two-factor recovery code
    cy.get('[data-cy="2fa-recovery-form"]').within(() => {
      cy.get('[data-cy="submit-button"]').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.getFormInput('Recovery code').clear().invoke('removeAttr', 'required')
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The provided two factor authentication code was invalid.')
    })

    // Invalid two-factor recovery code
    cy.get('[data-cy="2fa-recovery-form"]').within(() => {
      cy.getFormInput('Recovery code').type('invalid-code')
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The provided two factor recovery code was invalid.')
    })
  })

  it('should show an error if the two-factor recovery code is used twice', () => {
    cy.get('@loginForm').within(() => {
      cy.get('@emailInput').type('john.doe+tfa@example.com')
      cy.get('@passwordInput').type('password')
      cy.get('@submitButton').click()
    })

    cy.assertRedirect('two-factor-challenge')

    cy.get('[data-cy="switch-mode-button"]').click()

    cy.get('[data-cy="2fa-recovery-form"]').within(() => {
      cy.getFormInput('Recovery code').type('recovery-code')
      cy.get('[data-cy="submit-button"]').click()
    })

    cy.assertRedirect('links')

    cy.logout()
    cy.visit({ route: 'login' })

    cy.get('@loginForm').within(() => {
      cy.getFormInput('Email').type('john.doe+tfa@example.com')
      cy.getFormInput('Password').type('password')
      cy.get('@submitButton').click()
    })

    cy.assertRedirect('two-factor-challenge')

    cy.get('[data-cy="switch-mode-button"]').click()

    cy.get('[data-cy="2fa-recovery-form"]').within(() => {
      cy.getFormInput('Recovery code').type('recovery-code')
      cy.get('[data-cy="submit-button"]').click()

      cy.get('[data-cy="input-error-message"]').should('contain', 'The provided two factor recovery code was invalid.')
    })
  })
})