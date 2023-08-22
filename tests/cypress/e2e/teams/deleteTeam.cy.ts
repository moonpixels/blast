import { createUser, switchTeam } from '../../support/functions'

describe('Delete team', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    createUser({}, ['withOwnedTeam'])

    cy.login({ attributes: { email: 'user@blst.to' } })
    cy.visit({ route: 'links.index' })

    cy.get('[data-cy="main-navigation"]').within(() => {
      cy.get('a').contains('Team').click()
    })
  })

  it('should not allow users to delete a personal team', () => {
    cy.get('[data-cy="delete-personal-team-alert"]').should('exist')
    cy.get('[data-cy="delete-team-button"]').should('not.exist')
  })

  it('should allow owners to delete a team', () => {
    switchTeam('Owned Team')

    cy.get('[data-cy="delete-team-button"]').click()

    cy.get('[data-cy="delete-team-modal"]').within(() => {
      cy.get('[data-cy="delete-team-button"]').click()
    })

    cy.get('[data-cy="success-notification"]').should('contain', 'Team deleted')

    cy.assertRedirect('links')
    cy.get('[data-cy="team-switcher-button"]').should('contain', 'Personal Team')
  })

  it('should allow owners to cancel deleting a team', () => {
    switchTeam('Owned Team')

    cy.get('[data-cy="delete-team-button"]').click()

    cy.get('[data-cy="delete-team-modal"]').within(() => {
      cy.get('[data-cy="cancel-button"]').click()
    })

    cy.get('[data-cy="delete-team-modal"]').should('not.exist')

    cy.get('[data-cy="team-switcher-button"]').should('contain', 'Owned Team')
  })
})
