import { createUser, switchTeam } from '../../support/functions'

describe('Team members', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    createUser({}, ['withStandardTeam', 'withTeamMembership'])

    cy.login({ attributes: { email: 'user@blst.to' } })

    cy.visit({ route: 'links.index' })

    switchTeam('Standard Team')
  })

  it('shows an empty state when there are no members', () => {
    cy.get('[data-cy="no-members-empty-state"]').should('exist')
    cy.get('[data-cy="members-list"]').should('not.exist')
  })

  it('allows owners to delete team members', () => {
    createTeamMember()

    cy.get('[data-cy="members-list"]').should('exist')
    cy.get('[data-cy="members-list"]')
      .children()
      .first()
      .within(() => {
        cy.get('[data-cy="delete-team-member-button"]').click()
      })

    cy.get('[data-cy="delete-team-member-modal"]').within(() => {
      cy.get('[data-cy="delete-team-member-button"]').click()
    })

    cy.get('[data-cy="delete-team-member-modal"]').should('not.exist')

    cy.get('[data-cy="success-notification"]').should('contain', 'Member removed')
  })

  it('allows owners to cancel deleting team members', () => {
    createTeamMember()

    cy.get('[data-cy="members-list"]').should('exist')
    cy.get('[data-cy="members-list"]')
      .children()
      .first()
      .within(() => {
        cy.get('[data-cy="delete-team-member-button"]').click()
      })

    cy.get('[data-cy="delete-team-member-modal"]').within(() => {
      cy.get('[data-cy="cancel-button"]').click()
    })

    cy.get('[data-cy="delete-team-member-modal"]').should('not.exist')

    cy.get('[data-cy="success-notification"]').should('not.exist')
  })

  it.only('allows team members to leave the team', () => {
    switchTeam('Membership Team')

    cy.get('[data-cy="leave-team-button"]').click()

    cy.get('[data-cy="leave-team-modal"]').within(() => {
      cy.get('[data-cy="leave-team-button"]').click()
    })

    cy.get('[data-cy="leave-team-modal"]').should('not.exist')

    cy.get('[data-cy="success-notification"]').should('contain', 'You have left the team')
  })

  function createTeamMember(): void {
    cy.currentUser().then((user) => {
      cy.create({
        model: 'App\\Models\\TeamMembership',
        attributes: {
          team_id: user.current_team_id,
        },
      }).then(() => {
        cy.reload()
      })
    })
  }
})
