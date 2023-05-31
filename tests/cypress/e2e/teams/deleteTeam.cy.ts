import { createUser } from '../../support/functions'

describe('Delete team', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    createUser({}, ['withStandardTeam'])

    cy.login({ attributes: { email: 'john.doe@example.com' } })
    cy.visit({ route: 'links.index' })

    cy.get('[data-cy="main-navigation"]').within(() => {
      cy.get('a').contains('Team').click()
    })
  })

  it('should not allow users to delete a personal team', () => {
    cy.get('[data-cy="personal-team-alert"]').should('exist')
    cy.get('[data-cy="delete-team-button"]').should('not.exist')
  })

  it('should allow users to delete a team', () => {
    switchTeam('Standard Team')

    cy.get('[data-cy="delete-team-button"]').click()

    cy.get('[data-cy="delete-team-modal"]').within(() => {
      cy.get('[data-cy="delete-team-button"]').click()
    })

    cy.get('[data-cy="success-notification"]').should('contain', 'Team deleted')

    cy.assertRedirect('links')
    cy.get('[data-cy="team-switcher-button"]').should('contain', 'Personal Team')
  })

  it('should allow users to cancel deleting a team', () => {
    switchTeam('Standard Team')

    cy.get('[data-cy="delete-team-button"]').click()

    cy.get('[data-cy="delete-team-modal"]').within(() => {
      cy.get('[data-cy="cancel-button"]').click()
    })

    cy.get('[data-cy="delete-team-modal"]').should('not.exist')

    cy.get('[data-cy="team-switcher-button"]').should('contain', 'Standard Team')
  })

  function switchTeam(name: string): void {
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
})