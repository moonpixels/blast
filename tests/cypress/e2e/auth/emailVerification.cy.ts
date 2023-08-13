import { createUser } from '../../support/functions'

describe('Email verification', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    createUser({}, ['unverified'])

    cy.login({ attributes: { email: 'user@blst.to' } })

    cy.visit({ route: 'verification.notice' })
  })

  it('should show the email verification page', () => {
    cy.get('h2').should('contain', 'Verify your email address')

    cy.get('[data-cy="email-verification-alert"]').should(
      'contain.text',
      'We have sent an email to  user@blst.to  with a verification link. Please click on the link to verify your email address.'
    )

    cy.get('[data-cy="resend-email-button"]').should('contain', 'click here to request another.')
  })

  it('should allow users to resend the verification email', () => {
    cy.get('[data-cy="resend-email-button"]').click()

    cy.get('[data-cy="success-notification"]').should('contain', 'Email verification resent')
  })
})
