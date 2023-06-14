import { createUser, switchTeam } from '../../support/functions'

describe('Show team', () => {
  beforeEach(() => {
    cy.refreshDatabase()

    createUser({}, ['withStandardTeam', 'withTeamMembership'])

    cy.login({ attributes: { email: 'user@blst.to' } })

    cy.visit({ route: 'links.index' })

    switchTeam('Standard Team')
  })

  it('shows the owner the correct sections', () => {
    const sections: string[] = ['General settings', 'Members', 'Delete team']

    cy.get('h2').each(($el, index) => {
      cy.wrap($el).should('contain', sections[index])
    })
  })

  it('shows team members the correct sections', () => {
    const sections: string[] = ['Leave team']

    switchTeam('Membership Team')

    cy.get('h2').each(($el, index) => {
      cy.wrap($el).should('contain', sections[index])
    })
  })
})
