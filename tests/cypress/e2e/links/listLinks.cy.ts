import { createUser, switchTeam } from '../../support/functions'

describe('List links', () => {
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

    cy.visit({ route: 'links.index' })
  })

  it('shows an empty state when there are no links', () => {
    cy.get('[data-cy="no-links-empty-state"]').should('exist')
    cy.get('[data-cy="links-list"]').should('not.exist')
  })

  it('should show the links list', () => {
    cy.create({
      model: 'App\\Domain\\Link\\Models\\Link',
      attributes: {
        team_id: teamId,
      },
      count: 3,
    }).then(() => {
      cy.reload()

      cy.get('[data-cy="links-list"]').children().should('have.length', 3)
    })
  })

  it('should show pagination links if there are more than 15 links', () => {
    cy.create({
      model: 'App\\Domain\\Link\\Models\\Link',
      attributes: {
        team_id: teamId,
      },
      count: 16,
    }).then(() => {
      cy.reload()

      cy.get('[data-cy="links-list"]').children().should('have.length', 15)
      cy.get('[data-cy="pagination-totals"]').should('exist').and('contain', 'Showing 1 to 15 of 16 links')

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

      cy.get('[data-cy="links-list"]').children().should('have.length', 1)
      cy.get('[data-cy="pagination-totals"]').should('exist').and('contain', 'Showing 16 to 16 of 16 links')

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

      cy.get('[data-cy="links-list"]').children().should('have.length', 15)
    })
  })

  it('should not show pagination links if there are 15 or less links', () => {
    cy.create({
      model: 'App\\Domain\\Link\\Models\\Link',
      attributes: {
        team_id: teamId,
      },
      count: 15,
    }).then(() => {
      cy.reload()

      cy.get('[data-cy="links-list"]').children().should('have.length', 15)

      cy.get('[data-cy="pagination-totals"]').should('exist').and('contain', 'Showing 1 to 15 of 15 links')
      cy.get('[data-cy="pagination-previous-link"]').should('not.exist')
      cy.get('[data-cy="pagination-next-link"]').should('not.exist')
    })
  })

  it('should allow each link to be copied to the clipboard', () => {
    cy.create({
      model: 'App\\Domain\\Link\\Models\\Link',
      attributes: {
        team_id: teamId,
      },
      count: 2,
    }).then(() => {
      cy.reload()

      cy.get('[data-cy="links-list"]')
        .children()
        .should('have.length', 2)
        .each(($el) => {
          cy.wrap($el).within(() => {
            cy.get('[data-cy="copy-to-clipboard-button"]').click()
            cy.assertValueCopiedToClipboard($el.find('[data-cy="short-url-link"]').text())
          })
        })
    })
  })

  it('should allows links to be searched', () => {
    cy.create({
      model: 'App\\Domain\\Link\\Models\\Link',
      attributes: {
        team_id: teamId,
        alias: 'myAlias',
      },
    }).then(() => {
      cy.reload()

      cy.get('[data-cy="links-list"]').children().should('have.length', 1)

      cy.get('[data-cy="search-links-input"]').type('myAlias')
      cy.get('[data-cy="links-list"]').children().should('have.length', 1)

      cy.get('[data-cy="search-links-input"]').clear().type('notFound')
      cy.get('[data-cy="no-links-empty-state"]').should('exist')
    })
  })

  it('should indicate if a link has additional features set', () => {
    cy.create({
      model: 'App\\Domain\\Link\\Models\\Link',
      state: ['withPassword', 'expired', 'withReachedVisitLimit'],
      attributes: {
        team_id: teamId,
      },
    }).then(() => {
      cy.reload()

      cy.get('[data-cy="links-list"]').children().should('have.length', 1)

      cy.get('[data-cy="links-list"]')
        .children()
        .first()
        .within(() => {
          cy.get('[data-cy="password-protected-icon"]').should('exist')
          cy.get('[data-cy="expiry-date-icon"]').should('exist')
          cy.get('[data-cy="visit-limit-icon"]').should('exist')
        })
    })
  })
})
