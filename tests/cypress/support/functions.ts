import Chainable = Cypress.Chainable
import { CurrentUser } from '@/types/models'

/**
 * Get the OTP code for the given user.
 */
export function getOtpCodeForUser(user: CurrentUser): Chainable<string> {
  return cy
    .php(
      `app(PragmaRX\\Google2FA\\Google2FA::class)->getCurrentOtp(Illuminate\\Support\\Facades\\Crypt::decrypt(App\\Models\\User::find('${user.id}')->two_factor_secret));`
    )
    .then((response) => {
      expect(response).to.be.a('string')
      return response
    })
}

/**
 * Create a new user.
 */
export function createUser(
  attributes: Partial<CurrentUser> = {},
  state: string[] = [],
  load: string[] = []
): Chainable<CurrentUser> {
  if (Object.keys(attributes).length === 0) {
    attributes = {
      email: 'user@blst.to',
    }
  }

  return cy
    .create({
      model: 'App\\Models\\User',
      attributes,
      state,
      load,
    })
    .then((model) => {
      return model
    })
}

/**
 * Switch the users current team.
 */
export function switchTeam(name: string): void {
  cy.get('[data-cy="team-switcher-button"]').click()

  cy.get('[data-cy="team-switcher-menu"]').within(() => {
    cy.contains('button', name).click()
  })

  cy.get('[data-cy="team-switcher-menu"]').should('not.exist')

  cy.assertRedirect('links')
  cy.get('[data-cy="team-switcher-button"]').should('contain', name)

  cy.get('[data-cy="main-navigation"]').within(() => {
    cy.get('a').contains('Team').click()
  })
}
