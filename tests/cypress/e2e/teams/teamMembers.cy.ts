import { createUser, switchTeam } from '../../support/functions'

describe('Team members', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    createUser({}, ['withStandardTeam'])

    cy.login({ attributes: { email: 'user@blst.to' } })
    cy.visit({ route: 'links.index' })

    switchTeam('Standard Team')

    cy.get('[data-cy="invite-team-member-button"]').as('inviteTeamMemberButton')
    cy.get('[data-cy="switch-view-mode-button"]').as('switchViewModeButton')
  })
})
