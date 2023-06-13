import { createUser, getOtpCodeForUser } from '../../support/functions'
import { CurrentUser, User } from '@/types/models'

describe('Two factor authentication', () => {
  let user: CurrentUser

  function loginUser(user: User): void {
    cy.logout()
    cy.login({ attributes: { email: user.email } })
    cy.confirmPassword('password')
    cy.visit({ route: 'user.edit' })
  }

  beforeEach(() => {
    cy.refreshDatabase()

    createUser().then((model) => {
      user = model
    })

    createUser(
      {
        email: 'john.doe+tfa@blst.to',
      },
      ['withTwoFactorAuthentication']
    )

    cy.login({ attributes: { email: 'john.doe+tfa@blst.to' } })
    cy.confirmPassword('password')
    cy.visit({ route: 'user.edit' })

    cy.get('[data-cy="2fa-form"]').as('2faForm')
  })

  it('should allow users to enable two factor authentication', () => {
    loginUser(user)

    cy.get('@2faForm').within(() => {
      cy.get('[data-cy="enable-2fa-button"]').click()

      cy.get('[data-cy="2fa-qr-code"]').should('exist')
      cy.get('[data-cy="2fa-qr-code"]').find('svg').should('exist')

      cy.get('[data-cy="2fa-recovery-codes"]').should('exist')
      cy.get('[data-cy="2fa-recovery-codes"]').children().should('have.length', 8)

      getOtpCodeForUser(user).then((otpCode) => {
        cy.getFormInput('Two-factor code').type(otpCode)
      })

      cy.get('[data-cy="submit-button"]').click()

      cy.get('[data-cy="disable-2fa-button"]').should('exist')
    })

    cy.get('@2faForm').should('contain', 'Enabled')
  })

  it('should allow users to disable two factor authentication', () => {
    cy.get('@2faForm').within(() => {
      cy.get('[data-cy="disable-2fa-button"]').click()
      cy.get('[data-cy="enable-2fa-button"]').should('exist')
    })

    cy.get('@2faForm').should('contain', 'Disabled')
  })

  it('should show an error if the two factor code is invalid', () => {
    loginUser(user)

    // Missing two-factor code
    cy.get('@2faForm').within(() => {
      cy.get('[data-cy="enable-2fa-button"]').click()

      cy.get('[data-cy="submit-button"]').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.getFormInput('Two-factor code').clear().invoke('removeAttr', 'required')
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="input-error-message"]').should(
        'contain',
        'The provided two factor authentication code was invalid.'
      )
    })

    // Invalid two-factor code
    cy.get('@2faForm').within(() => {
      cy.getFormInput('Two-factor code').type('123456')
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="input-error-message"]').should(
        'contain',
        'The provided two factor authentication code was invalid.'
      )
    })
  })
})
