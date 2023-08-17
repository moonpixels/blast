import { createLink } from '../../support/functions'

describe('Redirect', () => {
  beforeEach(() => {
    cy.refreshDatabase()
  })

  it('should redirect the user to the destination URL when the password is correct', () => {
    createLink({ alias: 'passwordTest' }, ['withPassword', 'toExampleDotCom']).then((link) => {
      cy.visit({ route: 'redirect', parameters: { link: link.alias } })

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
    createLink({ alias: 'passwordTest' }, ['withPassword', 'toExampleDotCom']).then((link) => {
      cy.visit({ route: 'redirect', parameters: { link: link.alias } })

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

  it('should redirect the user to the expired link page if the link has expired', () => {
    createLink({ alias: 'expiredTest' }, ['expired']).then((link) => {
      cy.log('link', link)
      cy.visit({ route: 'redirect', parameters: { link: link.alias } })
      cy.assertRedirect('expired')
    })
  })

  it('should redirect the user to the visit limit reached page if the link has reached its visit limit', () => {
    createLink({ alias: 'visitLimitTest' }, ['withReachedVisitLimit']).then((link) => {
      cy.visit({ route: 'redirect', parameters: { link: link.alias } })
      cy.assertRedirect('reached-visit-limit')
    })
  })
})
