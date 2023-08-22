import { createUser } from '../../support/functions'

describe('Switch team', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    createUser({}, ['withOwnedTeam'])

    cy.login({ attributes: { email: 'user@blst.to' } })
    cy.visit({ route: 'links.index' })

    cy.get('[data-cy="team-switcher-button"]').as('teamSwitcherButton')
  })

  it('should allow users to switch teams', () => {
    cy.get('@teamSwitcherButton').click()

    cy.get('[data-cy="team-switcher-menu"]').within(() => {
      cy.get('button').should('have.length', 3)
      cy.get('button').contains('Personal Team').should('exist')
      cy.get('button').contains('Owned Team').should('exist')
      cy.contains('button', 'Owned Team').click()
    })

    cy.get('[data-cy="team-switcher-menu"]').should('not.exist')

    cy.assertRedirect('links')
    cy.get('@teamSwitcherButton').should('contain', 'Owned Team')
  })
})
