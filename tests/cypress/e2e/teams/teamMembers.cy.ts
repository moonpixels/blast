import { createUser, switchTeam } from '../../support/functions'
import { User } from '@/types/models'

describe('Team members', () => {
  let teamId: string

  beforeEach(() => {
    cy.refreshDatabase()

    createUser({}, ['withStandardTeam', 'withTeamMembership'])

    cy.login({ attributes: { email: 'user@blst.to' } })

    cy.visit({ route: 'links.index' })

    switchTeam('Standard Team')

    cy.currentUser().then((user) => {
      teamId = user.current_team_id
    })
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

  it('allows team members to leave the team', () => {
    switchTeam('Membership Team')

    cy.get('[data-cy="leave-team-button"]').click()

    cy.get('[data-cy="leave-team-modal"]').within(() => {
      cy.get('[data-cy="leave-team-button"]').click()
    })

    cy.get('[data-cy="leave-team-modal"]').should('not.exist')

    cy.get('[data-cy="success-notification"]').should('contain', 'You have left the team')
  })

  it.only('should allow owners to filter team members', () => {
    cy.create({
      model: 'App\\Models\\User',
      attributes: {
        name: 'John Doe',
        email: 'john.doe@blst.to',
      },
    }).then((user: User) => {
      cy.create({
        model: 'App\\Models\\TeamMembership',
        attributes: {
          team_id: teamId,
          user_id: user.id,
        },
      }).then(() => {
        cy.create({
          model: 'App\\Models\\TeamMembership',
          attributes: {
            team_id: teamId,
          },
        }).then(() => {
          cy.reload()
          cy.get('[data-cy="members-list"]').children().should('have.length', 2)

          cy.get('[data-cy="search-members-input"]').type('@blst.to')

          cy.get('[data-cy="members-list"]').children().should('have.length', 1)
          cy.get('[data-cy="members-list"]').within(() => {
            cy.contains('john.doe@blst.to').should('exist')
          })
        })
      })
    })
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
