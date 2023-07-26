import { createLink } from '../../support/functions'

describe('Redirect', () => {
  beforeEach(() => {
    cy.refreshDatabase()
  })

  it('should redirect the user to the destination URL when the password is correct', () => {
    createLink({}, ['withPassword', 'toExampleDotCom']).then((link) => {
      cy.visit({ route: 'redirect', parameters: { alias: link.alias } })

      cy.get('[data-cy="authenticated-redirect-form"]')
        .within(() => {
          cy.getFormInput('Password').type('password')
          cy.get('[data-cy="submit-button"]').click()
        })
        .then(() => {
          cy.on('url:changed', (newUrl) => {
            expect(newUrl).to.include(link.destination_url)
          })
        })
    })
  })

  it('should show an error if the password is invalid', () => {
    createLink({}, ['withPassword', 'toExampleDotCom']).then((link) => {
      cy.visit({ route: 'redirect', parameters: { alias: link.alias } })

      // Missing password
      cy.get('[data-cy="authenticated-redirect-form"]').within(() => {
        cy.get('[data-cy="submit-button"]').click()
        cy.get('input:invalid').should('have.length', 1)

        cy.getFormInput('Password').invoke('removeAttr', 'required')
        cy.get('[data-cy="submit-button"]').click()
        cy.get('[data-cy="input-error-message"]').should('contain', 'The password field is required.')
      })

      // Invalid password
      cy.get('[data-cy="authenticated-redirect-form"]').within(() => {
        cy.getFormInput('Password').type('wrong password')
        cy.get('[data-cy="submit-button"]').click()
        cy.get('[data-cy="input-error-message"]').should('contain', 'The provided password is incorrect.')
      })
    })
  })
})
