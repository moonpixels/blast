import { createUser, switchTeam } from '../../support/functions'

describe('Team members', () => {
  let teamId: string

  beforeEach(() => {
    cy.refreshDatabase()

    createUser({}, ['withStandardTeam', 'withTeamMembership']).then((user) => {
      cy.create({
        model: 'App\\Domain\\Team\\Models\\Team',
        attributes: {
          name: 'Empty Team',
          owner_id: user.id,
        },
      })
    })

    cy.login({ attributes: { email: 'user@blst.to' } })

    cy.visit({ route: 'links.index' })

    switchTeam('Standard Team')

    cy.currentUser().then((user) => {
      teamId = user.current_team_id

      cy.php(
        `App\\Domain\\Team\\Models\\Team::find('${teamId}')->members()->attach(App\\Domain\\User\\Models\\User::factory(15)->create()); return true;`
      ).then(() => {
        cy.reload()
      })
    })
  })

  it('shows an empty state when there are no members', () => {
    switchTeam('Empty Team')

    cy.get('[data-cy="no-members-empty-state"]').should('exist')
    cy.get('[data-cy="members-list"]').should('not.exist')
  })

  it('allows owners to delete team members', () => {
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

  it('should allow owners to filter team members', () => {
    cy.get('[data-cy="members-list"]').children().should('have.length', 10)

    let firstMemberName: string

    cy.get('[data-cy="members-list"]')
      .children()
      .first()
      .within(() => {
        cy.get('[data-cy="team-member-name"]').then((element) => {
          firstMemberName = element.text()
        })
      })
      .then(() => {
        cy.get('[data-cy="search-members-input"]').type(firstMemberName)

        cy.get('[data-cy="members-list"]').children().should('have.length', 1)
        cy.get('[data-cy="members-list"]').within(() => {
          cy.contains(firstMemberName).should('exist')
        })
      })
  })

  it('should show pagination links if there are more than 10 team members', () => {
    cy.get('[data-cy="members-list"]').children().should('have.length', 10)
    cy.get('[data-cy="pagination-totals"]').should('exist').and('contain', 'Showing 1 to 10 of 15 members')

    cy.get('[data-cy="pagination-previous-link"]')
      .should('exist')
      .within(() => {
        cy.get('button').should('have.attr', 'disabled')
      })

    cy.get('[data-cy="pagination-next-link"]')
      .should('exist')
      .within(() => {
        cy.get('button').should('not.have.attr', 'disabled')
      })
      .click()

    cy.get('[data-cy="members-list"]').children().should('have.length', 5)
    cy.get('[data-cy="pagination-totals"]').should('exist').and('contain', 'Showing 11 to 15 of 15 members')

    cy.get('[data-cy="pagination-next-link"]')
      .should('exist')
      .within(() => {
        cy.get('button').should('have.attr', 'disabled')
      })

    cy.get('[data-cy="pagination-previous-link"]')
      .should('exist')
      .within(() => {
        cy.get('button').should('not.have.attr', 'disabled')
      })
      .click()

    cy.get('[data-cy="members-list"]').children().should('have.length', 10)
  })
})
