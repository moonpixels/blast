import { createUser } from '../../support/functions'

describe('Create link', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    createUser({}, ['withStandardTeam', 'withTeamMembership'])

    cy.login({ attributes: { email: 'user@blst.to' } })
    cy.visit({ route: 'links.index' })

    cy.get('[data-cy="link-shortener-form"]').as('linkShortenerForm')
  })

  it('should allow users to create a link and copy it to their clipboard', () => {
    cy.get('@linkShortenerForm').within(() => {
      cy.getFormInput('URL').type('https://blst.to')
      cy.get('[data-cy="submit-button"]').click()
    })

    cy.get('[data-cy="shortened-link-card"]').should('be.visible')

    cy.get('[data-cy="shortened-link-card"]').within(() => {
      cy.get('[data-cy="shortened-link-link"]').should('have.attr', 'href', 'https://blst.to')
      cy.get('[data-cy="shortened-link-destination"]').should('contain', 'https://blst.to')
      cy.get('[data-cy="copy-to-clipboard-button"]').click()
      cy.assertValueCopiedToClipboard('https://blst.to')
    })
  })

  it('should show an error if the URL is invalid', () => {
    // Missing URL
    cy.get('@linkShortenerForm').within(() => {
      cy.get('[data-cy="submit-button"]').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.getFormInput('URL').invoke('removeAttr', 'required')
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The URL field is required.')
    })

    // Invalid URL
    cy.get('@linkShortenerForm').within(() => {
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

  it('should allow users to create a link with a custom alias', () => {
    cy.get('@linkShortenerForm').within(() => {
      cy.getFormInput('URL').type('https://blst.to')

      cy.get('[data-cy="set-alias-button"]').click()

      cy.get('[data-cy="link-options-popover"]').within(() => {
        cy.getFormInput('Alias').type('customAlias')
        cy.get('[data-cy="dismiss-options-popover-button"]').click()
      })

      cy.get('[data-cy="submit-button"]').click()
    })

    cy.get('[data-cy="shortened-link-card"]').should('be.visible')

    cy.get('[data-cy="shortened-link-card"]').within(() => {
      cy.get('[data-cy="shortened-link-link"]').should('contain', 'customAlias')
    })
  })

  it('should show an error if the alias is invalid', () => {
    cy.get('@linkShortenerForm').within(() => {
      cy.getFormInput('URL').type('https://blst.to')

      // Invalid characters
      cy.get('[data-cy="set-alias-button"]').click()
      cy.get('[data-cy="link-options-popover"]').within(() => {
        cy.getFormInput('Alias').type('!@#$%^&*()')
        cy.get('[data-cy="dismiss-options-popover-button"]').click()
      })
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="link-options-errors"]').should(
        'contain',
        'The alias field must only contain letters and numbers.'
      )

      // Too long
      cy.get('[data-cy="set-alias-button"]').click()
      cy.get('[data-cy="link-options-popover"]').within(() => {
        cy.getFormInput('Alias').clear().type('a'.repeat(21))
        cy.get('[data-cy="dismiss-options-popover-button"]').click()
      })
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="link-options-errors"]').should(
        'contain',
        'The alias field must not be greater than 20 characters.'
      )
    })

    // Already taken
    cy.get('@linkShortenerForm').within(() => {
      cy.get('[data-cy="set-alias-button"]').click()
      cy.get('[data-cy="link-options-popover"]').within(() => {
        cy.getFormInput('Alias').clear().type('customAlias')
        cy.get('[data-cy="dismiss-options-popover-button"]').click()
      })

      cy.get('[data-cy="submit-button"]').click()
    })

    cy.get('[data-cy="shortened-link-card"]').should('be.visible')

    cy.get('@linkShortenerForm').within(() => {
      cy.getFormInput('URL').type('https://blst.to')

      cy.get('[data-cy="set-alias-button"]').click()

      cy.get('[data-cy="link-options-popover"]').within(() => {
        cy.getFormInput('Alias').clear().type('customAlias')
        cy.get('[data-cy="dismiss-options-popover-button"]').click()
      })

      cy.get('[data-cy="submit-button"]').click()

      cy.get('[data-cy="link-options-errors"]').should('contain', 'The alias has already been taken.')
    })
  })

  it('should allow users to choose a team to create a link for', () => {
    cy.get('@linkShortenerForm').within(() => {
      cy.getFormInput('URL').type('https://blst.to')

      cy.get('[data-cy="set-team_id-button"]').click()

      cy.get('[data-cy="link-options-popover"]').within(() => {
        cy.getFormInput('Team').select('Standard Team')
        cy.get('[data-cy="dismiss-options-popover-button"]').click()
      })

      cy.get('[data-cy="submit-button"]').click()
    })

    cy.get('[data-cy="shortened-link-card"]').should('be.visible')
  })
})
