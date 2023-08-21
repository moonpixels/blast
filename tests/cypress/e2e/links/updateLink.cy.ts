import { createUser, switchTeam } from '../../support/functions'
import dayjs from 'dayjs'

describe('Update links', () => {
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

  it('should allow users to update a link', () => {
    cy.create({
      model: 'App\\Domain\\Link\\Models\\Link',
      attributes: {
        team_id: teamId,
      },
      count: 1,
    }).then(() => {
      cy.reload()

      cy.get('[data-cy="links-list"]').children().should('have.length', 1)

      cy.get('[data-cy="link-options-menu-button"]').click()
      cy.get('[data-cy="link-options-menu"]').within(() => {
        cy.get('[data-cy="update-link-button"]').click()
      })

      cy.get('[data-cy="update-link-modal"]').within(() => {
        cy.get('[data-cy="update-link-form"]').within(() => {
          cy.getFormInput('URL').type('https://www.blst.to')
          cy.getFormInput('Alias').type('test')
          cy.getFormInput('Expires at').type(dayjs().add(1, 'day').format('DD/MM/YYYY HH:mm'))
          cy.getFormInput('Visit limit').type('10')
          cy.getFormInput('Team').select('Owned Team')
          cy.getFormInput('Require a password to access the link').click()
          cy.getFormInput('Password').type('password')
        })

        cy.get('[data-cy="submit-button"]').click()
      })

      cy.get('[data-cy="success-notification"]').should('contain', 'Link updated')
    })
  })

  it('should show an error if the URL is invalid', () => {
    cy.create({
      model: 'App\\Domain\\Link\\Models\\Link',
      attributes: {
        team_id: teamId,
      },
      count: 1,
    }).then(() => {
      cy.reload()

      cy.get('[data-cy="links-list"]').children().should('have.length', 1)

      cy.get('[data-cy="link-options-menu-button"]').click()
      cy.get('[data-cy="link-options-menu"]').within(() => {
        cy.get('[data-cy="update-link-button"]').click()
      })

      cy.get('[data-cy="update-link-modal"]').within(() => {
        // Missing URL
        cy.getFormInput('URL').clear()
        cy.get('[data-cy="submit-button"]').click()
        cy.get('input:invalid').should('have.length', 1)

        cy.getFormInput('URL').invoke('removeAttr', 'required')
        cy.get('[data-cy="submit-button"]').click()
        cy.get('[data-cy="input-error-message"]').should('contain', 'The URL field is required.')

        // Invalid URL
        cy.getFormInput('URL').type('not a url')
        cy.get('[data-cy="submit-button"]').click()
        cy.get('[data-cy="input-error-message"]').should('contain', 'The URL field must be a valid URL.')

        cy.getFormInput('URL')
          .clear()
          .type('https://blst.to/' + 'a'.repeat(2033))
        cy.get('[data-cy="submit-button"]').click()
        cy.get('[data-cy="input-error-message"]').should(
          'contain',
          'The URL field must not be greater than 2048 characters.'
        )
      })
    })
  })

  it('should show an error if the alias is invalid', () => {
    cy.create({
      model: 'App\\Domain\\Link\\Models\\Link',
      attributes: {
        team_id: teamId,
      },
      count: 1,
    }).then(() => {
      cy.reload()

      cy.get('[data-cy="links-list"]').children().should('have.length', 1)

      cy.get('[data-cy="link-options-menu-button"]').click()
      cy.get('[data-cy="link-options-menu"]').within(() => {
        cy.get('[data-cy="update-link-button"]').click()
      })

      cy.get('[data-cy="update-link-modal"]').within(() => {
        // Invalid characters
        cy.getFormInput('Alias').type('!@#$%^&*()')
        cy.get('[data-cy="submit-button"]').click()
        cy.get('[data-cy="input-error-message"]').should(
          'contain',
          'The alias field must only contain letters and numbers.'
        )

        // Too long
        cy.getFormInput('Alias').clear().type('a'.repeat(21))
        cy.get('[data-cy="submit-button"]').click()
        cy.get('[data-cy="input-error-message"]').should(
          'contain',
          'The alias field must not be greater than 20 characters.'
        )

        // Reserved
        cy.getFormInput('Alias').clear().type('admin')
        cy.get('[data-cy="submit-button"]').click()
        cy.get('[data-cy="submit-button"]').click()
        cy.get('[data-cy="input-error-message"]').should('contain', 'The alias has already been taken.')

        // Route conflict
        cy.getFormInput('Alias').clear().type('login')
        cy.get('[data-cy="submit-button"]').click()
        cy.get('[data-cy="submit-button"]').click()
        cy.get('[data-cy="input-error-message"]').should('contain', 'The alias has already been taken.')
      })
    })
  })

  it('should not allow an invalid expires at date to be entered', () => {
    cy.create({
      model: 'App\\Domain\\Link\\Models\\Link',
      attributes: {
        team_id: teamId,
      },
      count: 1,
    }).then(() => {
      cy.reload()

      cy.get('[data-cy="links-list"]').children().should('have.length', 1)

      cy.get('[data-cy="link-options-menu-button"]').click()
      cy.get('[data-cy="link-options-menu"]').within(() => {
        cy.get('[data-cy="update-link-button"]').click()
      })

      cy.get('[data-cy="update-link-modal"]').within(() => {
        // Invalid date
        cy.getFormInput('Expires at').type('not a date')
        cy.getFormInput('Expires at').should('have.value', '')
      })
    })
  })

  it('should show an error if the visit limit is invalid', () => {
    cy.create({
      model: 'App\\Domain\\Link\\Models\\Link',
      attributes: {
        team_id: teamId,
      },
      count: 1,
    }).then(() => {
      cy.reload()

      cy.get('[data-cy="links-list"]').children().should('have.length', 1)

      cy.get('[data-cy="link-options-menu-button"]').click()
      cy.get('[data-cy="link-options-menu"]').within(() => {
        cy.get('[data-cy="update-link-button"]').click()
      })

      cy.get('[data-cy="update-link-modal"]').within(() => {
        // Invalid visit limit
        cy.getFormInput('Visit limit').type('not a number')
        cy.get('[data-cy="submit-button"]').click()
        cy.get('[data-cy="input-error-message"]').should('contain', 'The visit limit field must be an integer.')

        // Negative visit limit
        cy.getFormInput('Visit limit').clear().type('-1')
        cy.get('[data-cy="submit-button"]').click()
        cy.get('[data-cy="input-error-message"]').should('contain', 'The visit limit field must be at least 1.')

        // Too large visit limit
        cy.getFormInput('Visit limit').clear().type('16777216')
        cy.get('[data-cy="submit-button"]').click()
        cy.get('[data-cy="input-error-message"]').should(
          'contain',
          'The visit limit field must not be greater than 16777215.'
        )
      })
    })
  })

  it('should only show the password field when the password protected option is selected', () => {
    cy.create({
      model: 'App\\Domain\\Link\\Models\\Link',
      attributes: {
        team_id: teamId,
      },
      count: 1,
    }).then(() => {
      cy.reload()

      cy.get('[data-cy="links-list"]').children().should('have.length', 1)

      cy.get('[data-cy="link-options-menu-button"]').click()
      cy.get('[data-cy="link-options-menu"]').within(() => {
        cy.get('[data-cy="update-link-button"]').click()
      })

      cy.get('[data-cy="update-link-modal"]').within(() => {
        // Password protected
        cy.getFormInput('Require a password to access the link').check()
        cy.getFormInput('Password').should('exist')

        // Not password protected
        cy.getFormInput('Require a password to access the link').uncheck()
        cy.contains('label', 'Password').should('not.exist')
      })
    })
  })
})
