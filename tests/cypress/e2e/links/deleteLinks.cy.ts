import { createUser, switchTeam } from '../../support/functions'

describe('Delete links', () => {
  let teamId: string

  beforeEach(() => {
    cy.refreshDatabase()

    createUser({}, ['withOwnedTeam', 'withMemberTeam'])

    cy.login({ attributes: { email: 'user@blst.to' } })

    cy.visit({ route: 'links.index' })

    switchTeam('Owned Team')

    cy.currentUser().then((user) => {
      teamId = user.current_team_id
    })

    cy.visit({ route: 'links.index' })
  })

  it('should allow users to delete a link', () => {
    cy.create({
      model: 'Domain\\Link\\Models\\Link',
      attributes: {
        team_id: teamId,
      },
      count: 1,
    }).then(() => {
      cy.reload()

      cy.get('[data-cy="links-list"]').children().should('have.length', 1)

      cy.get('[data-cy="link-options-menu-button"]').click()
      cy.get('[data-cy="link-options-menu"]').within(() => {
        cy.get('[data-cy="delete-link-button"]').click()
      })

      cy.get('[data-cy="delete-link-modal"]').within(() => {
        cy.get('[data-cy="delete-link-button"]').click()
      })

      cy.get('[data-cy="success-notification"]').should('contain', 'Link deleted')

      cy.get('[data-cy="links-list"]').should('not.exist')
      cy.get('[data-cy="no-links-empty-state"]').should('exist')
    })
  })

  it('should allow users to cancel deleting a link', () => {
    cy.create({
      model: 'Domain\\Link\\Models\\Link',
      attributes: {
        team_id: teamId,
      },
      count: 1,
    }).then(() => {
      cy.reload()

      cy.get('[data-cy="links-list"]').children().should('have.length', 1)

      cy.get('[data-cy="link-options-menu-button"]').click()
      cy.get('[data-cy="link-options-menu"]').within(() => {
        cy.get('[data-cy="delete-link-button"]').click()
      })

      cy.get('[data-cy="delete-link-modal"]').within(() => {
        cy.get('[data-cy="cancel-button"]').click()
      })

      cy.get('[data-cy="links-list"]').children().should('have.length', 1)
      cy.get('[data-cy="no-links-empty-state"]').should('not.exist')
    })
  })
})
