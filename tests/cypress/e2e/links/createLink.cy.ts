import { createUser } from '../../support/functions'
import dayjs from 'dayjs'

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
      cy.get('[data-cy="short-url-link"]').should('have.attr', 'href', 'https://blst.to')
      cy.get('[data-cy="shortened-link-destination"]').should('contain', 'https://blst.to')
      cy.get('[data-cy="copy-to-clipboard-button"]').click()
      cy.assertValueCopiedToClipboard(Cypress.$('[data-cy="short-url-link"]').text())
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
      cy.get('[data-cy="short-url-link"]').should('contain', 'customAlias')
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

      // Reserved
      cy.get('[data-cy="set-alias-button"]').click()
      cy.get('[data-cy="link-options-popover"]').within(() => {
        cy.getFormInput('Alias').clear().type('admin')
        cy.get('[data-cy="dismiss-options-popover-button"]').click()
      })
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="link-options-errors"]').should('contain', 'The alias has already been taken.')

      // Route conflict
      cy.get('[data-cy="set-alias-button"]').click()
      cy.get('[data-cy="link-options-popover"]').within(() => {
        cy.getFormInput('Alias').clear().type('login')
        cy.get('[data-cy="dismiss-options-popover-button"]').click()
      })
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="link-options-errors"]').should('contain', 'The alias has already been taken.')
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

  it('should allow users to create a link with a password', () => {
    cy.get('@linkShortenerForm').within(() => {
      cy.getFormInput('URL').type('https://blst.to')

      cy.get('[data-cy="set-password-button"]').click()

      cy.get('[data-cy="link-options-popover"]').within(() => {
        cy.getFormInput('Password').type('password')
        cy.get('[data-cy="dismiss-options-popover-button"]').click()
      })

      cy.get('[data-cy="submit-button"]').click()
    })

    cy.get('[data-cy="shortened-link-card"]').should('be.visible')
  })

  it('should allow users to create a link with an expiry date', () => {
    cy.get('@linkShortenerForm').within(() => {
      cy.getFormInput('URL').type('https://blst.to')

      cy.get('[data-cy="set-expires_at-button"]').click()

      cy.get('[data-cy="link-options-popover"]').within(() => {
        cy.getFormInput('Expires at').type(dayjs().add(1, 'day').format('DD/MM/YYYY HH:mm'))
        cy.get('[data-cy="dismiss-options-popover-button"]').click()
      })

      cy.get('[data-cy="submit-button"]').click()
    })

    cy.get('[data-cy="shortened-link-card"]').should('be.visible')
  })

  it('should show an error if the expiry date is invalid', () => {
    cy.get('@linkShortenerForm').within(() => {
      cy.getFormInput('URL').type('https://blst.to')

      // Invalid date
      cy.get('[data-cy="set-expires_at-button"]').click()
      cy.get('[data-cy="link-options-popover"]').within(() => {
        cy.getFormInput('Expires at').type('invalid date')
        cy.getFormInput('Expires at').should('have.value', '')
        cy.get('[data-cy="dismiss-options-popover-button"]').click()
      })

      // Date in the past
      cy.get('[data-cy="set-expires_at-button"]').click()
      cy.get('[data-cy="link-options-popover"]').within(() => {
        cy.getFormInput('Expires at').type(dayjs().subtract(1, 'day').format('DD/MM/YYYY HH:mm'))
        cy.get('[data-cy="dismiss-options-popover-button"]').click()
      })
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="link-options-errors"]').should('contain', 'The expires at field must be a date after now.')
    })
  })

  it('should allow users to create a link with a visit limit', () => {
    cy.get('@linkShortenerForm').within(() => {
      cy.getFormInput('URL').type('https://blst.to')

      cy.get('[data-cy="set-visit_limit-button"]').click()

      cy.get('[data-cy="link-options-popover"]').within(() => {
        cy.getFormInput('Visit limit').type('10')
        cy.get('[data-cy="dismiss-options-popover-button"]').click()
      })

      cy.get('[data-cy="submit-button"]').click()
    })

    cy.get('[data-cy="shortened-link-card"]').should('be.visible')
  })

  it('should show an error if the visit limit is invalid', () => {
    cy.get('@linkShortenerForm').within(() => {
      cy.getFormInput('URL').type('https://blst.to')

      // Invalid visit limit
      cy.get('[data-cy="set-visit_limit-button"]').click()
      cy.get('[data-cy="link-options-popover"]').within(() => {
        cy.getFormInput('Visit limit').type('invalid visit limit')
        cy.get('[data-cy="dismiss-options-popover-button"]').click()
      })
      cy.get('[data-cy="link-options-popover"]').should('not.exist')
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="link-options-errors"]').should('contain', 'The visit limit field must be an integer.')

      // Negative visit limit
      cy.get('[data-cy="set-visit_limit-button"]').click()
      cy.get('[data-cy="link-options-popover"]').within(() => {
        cy.getFormInput('Visit limit').clear().type('-1')
        cy.get('[data-cy="dismiss-options-popover-button"]').click()
      })
      cy.get('[data-cy="link-options-popover"]').should('not.exist')
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="link-options-errors"]').should('contain', 'The visit limit field must be at least 1.')

      // Visit limit too high
      cy.get('[data-cy="set-visit_limit-button"]').click()
      cy.get('[data-cy="link-options-popover"]').within(() => {
        cy.getFormInput('Visit limit').clear().type('16777216')
        cy.get('[data-cy="dismiss-options-popover-button"]').click()
      })
      cy.get('[data-cy="link-options-popover"]').should('not.exist')
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="link-options-errors"]').should(
        'contain',
        'The visit limit field must not be greater than 16777215.'
      )
    })
  })
})
