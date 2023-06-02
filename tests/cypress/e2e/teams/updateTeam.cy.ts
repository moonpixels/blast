import { createUser } from '../../support/functions'

describe('Update team', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    createUser({}, ['withStandardTeam'])

    cy.login({ attributes: { email: 'john.doe@example.com' } })
    cy.visit({ route: 'links.index' })

    cy.get('[data-cy="main-navigation"]').within(() => {
      cy.get('a').contains('Team').click()
    })

    cy.get('[data-cy="general-settings-form"]')
      .as('generalSettingsForm')
      .within(() => {
        cy.getFormInput('Team name').as('nameInput')
        cy.get('[data-cy="submit-button"]').as('submitButton')
      })
  })

  it('should allow users to update a team', () => {
    cy.get('@generalSettingsForm').within(() => {
      cy.get('@nameInput').clear().type('New name')
      cy.get('@submitButton').click()
    })

    cy.get('[data-cy="success-notification"]').should('contain', 'Team updated')
  })

  it('should show an error if the team name is invalid', () => {
    // Missing name
    cy.get('@generalSettingsForm').within(() => {
      cy.get('@nameInput').clear()
      cy.get('@submitButton').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.get('@nameInput').clear().invoke('removeAttr', 'required')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The name field is required.')
    })

    // Invalid name
    cy.get('@generalSettingsForm').within(() => {
      cy.get('@nameInput').type('Standard Team')
      cy.get('@submitButton').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'You already have a team called Standard Team.')
    })
  })
})
