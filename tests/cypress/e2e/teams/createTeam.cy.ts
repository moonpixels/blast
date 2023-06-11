import { createUser } from '../../support/functions'

describe('Create team', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    createUser()

    cy.login({ attributes: { email: 'user@blst.to' } })
    cy.visit({ route: 'links.index' })

    cy.get('[data-cy="team-switcher-button"]').as('teamSwitcherButton')
  })

  it('should allow users to create a team', () => {
    cy.get('@teamSwitcherButton').click()

    cy.get('[data-cy="team-switcher-menu"]').within(() => {
      cy.get('[data-cy="create-team-button"]').click()
    })

    cy.get('[data-cy="create-team-modal"]').within(() => {
      cy.getFormInput('Team name').type('My Team')
      cy.get('[data-cy="submit-button"]').click()
    })

    cy.get('[data-cy="create-team-modal"]').should('not.exist')

    cy.assertRedirect('teams')
    cy.get('@teamSwitcherButton').should('contain', 'My Team')
  })

  it('should show an error if the name is invalid', () => {
    cy.get('@teamSwitcherButton').click()

    cy.get('[data-cy="team-switcher-menu"]').within(() => {
      cy.get('[data-cy="create-team-button"]').click()
    })

    // Missing name
    cy.get('[data-cy="create-team-modal"]').within(() => {
      cy.get('[data-cy="submit-button"]').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.getFormInput('Team name').invoke('removeAttr', 'required')
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The name field is required.')
    })

    // Invalid name
    cy.get('[data-cy="create-team-modal"]').within(() => {
      cy.getFormInput('Team name').type('Personal Team')
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'You already have a team called Personal Team.')
    })
  })

  it('should allow users to cancel creating a team', () => {
    cy.get('@teamSwitcherButton').click()

    cy.get('[data-cy="team-switcher-menu"]').within(() => {
      cy.get('[data-cy="create-team-button"]').click()
    })

    cy.get('[data-cy="create-team-modal"]').within(() => {
      cy.get('[data-cy="cancel-button"]').click()
    })

    cy.get('[data-cy="create-team-modal"]').should('not.exist')
  })
})
