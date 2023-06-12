import { createUser, switchTeam } from '../../support/functions'

describe('Team invitations', () => {
  let teamId: string

  beforeEach(() => {
    cy.refreshDatabase()

    createUser({}, ['withStandardTeam'])

    cy.login({ attributes: { email: 'user@blst.to' } })
    cy.visit({ route: 'links.index' })

    switchTeam('Standard Team')

    cy.currentUser().then((user) => {
      teamId = user.current_team_id
    })

    cy.get('[data-cy="invite-team-member-button"]').as('inviteTeamMemberButton')
    cy.get('[data-cy="switch-view-mode-button"]').as('switchViewModeButton')

    cy.get('@switchViewModeButton').click()
  })

  it('should not allow users to invite members to a personal team', () => {
    switchTeam('Personal Team')
    cy.get('[data-cy="manage-members-personal-team-alert"]').should('exist')
    cy.get('@inviteTeamMemberButton').should('not.exist')
  })

  it('should show an empty state when there are no invitations', () => {
    cy.get('[data-cy="no-invitations-empty-state"]').should('exist')
    cy.get('[data-cy="invitations-list"]').should('not.exist')
  })

  it('should allow owners to invite members to a team', () => {
    cy.get('@inviteTeamMemberButton').click()

    cy.get('[data-cy="invite-team-member-modal"]').within(() => {
      cy.getFormInput('Email').type('invited-user@blst.to')
      cy.get('[data-cy="submit-button"]').click()
    })

    cy.get('[data-cy="invite-team-member-modal"]').should('not.exist')

    cy.get('[data-cy="success-notification"]').should('contain', 'Invitation sent')

    cy.get('[data-cy="invitations-list"]').should('exist')
    cy.get('[data-cy="invitations-list"]').children().should('have.length', 1)
    cy.get('[data-cy="invitations-list"]').children().should('contain', 'invited-user@blst.to')
  })

  it('should allow owners to cancel inviting a member to a team', () => {
    cy.get('@inviteTeamMemberButton').click()

    cy.get('[data-cy="invite-team-member-modal"]').within(() => {
      cy.getFormInput('Email').type('invited-user@blst.to')
      cy.get('[data-cy="cancel-button"]').click()
    })

    cy.get('[data-cy="invite-team-member-modal"]').should('not.exist')

    cy.get('[data-cy="no-invitations-empty-state"]').should('exist')
    cy.get('[data-cy="invitations-list"]').should('not.exist')
  })

  it('should show an error if the invitees email is invalid', () => {
    cy.get('@inviteTeamMemberButton').click()

    // Missing email
    cy.get('[data-cy="invite-team-member-modal"]').within(() => {
      cy.get('[data-cy="submit-button"]').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.getFormInput('Email').clear().invoke('removeAttr', 'required')
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The email field is required.')
    })

    // Invalid email
    cy.get('[data-cy="invite-team-member-modal"]').within(() => {
      cy.getFormInput('Email').type('invalid-email')
      cy.get('[data-cy="submit-button"]').click()
      cy.get('input:invalid').should('have.length', 1)

      cy.getFormInput('Email').invoke('attr', 'type', 'text')
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="input-error-message"]').should('contain', 'The email field must be a valid email address.')
    })

    // Already invited
    cy.get('[data-cy="invite-team-member-modal"]').within(() => {
      cy.create({
        model: 'App\\Models\\TeamInvitation',
        attributes: {
          email: 'already-invited@blst.to',
          team_id: teamId,
        },
      })

      cy.getFormInput('Email').clear().type('already-invited@blst.to')
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="input-error-message"]').should(
        'contain',
        'You already have a pending invitation for this email address.'
      )
    })

    // Already a member
    cy.get('[data-cy="invite-team-member-modal"]').within(() => {
      cy.getFormInput('Email').clear().type('user@blst.to')
      cy.get('[data-cy="submit-button"]').click()
      cy.get('[data-cy="input-error-message"]').should(
        'contain',
        'There is already a team member with this email address.'
      )
    })
  })

  it('should allow a user to accept an invitation', () => {
    cy.create({
      model: 'App\\Models\\Team',
    }).then((team) => {
      cy.create({
        model: 'App\\Models\\TeamInvitation',
        attributes: {
          email: 'user@blst.to',
          team_id: team.id,
        },
      }).then((invitation) => {
        cy.visit(invitation.accept_url)

        cy.get('[data-cy="success-notification"]').should('contain', 'Invitation accepted')

        cy.get('[data-cy="team-switcher-button"]').click()
        cy.get('[data-cy="team-switcher-menu"]').within(() => {
          cy.contains('button', team.name).should('exist')
        })
      })
    })
  })

  it.only('should allow owners to cancel an invitation', () => {
    cy.create({
      model: 'App\\Models\\TeamInvitation',
      attributes: {
        team_id: teamId,
      },
    }).then(() => {
      cy.reload()
      cy.get('@switchViewModeButton').click()

      cy.get('[data-cy="invitations-list"]').within(() => {
        cy.get('[data-cy="cancel-invitation-button"]').click()
      })

      cy.get('[data-cy="success-notification"]').should('contain', 'Invitation cancelled')
      cy.get('[data-cy="no-invitations-empty-state"]').should('exist')
      cy.get('[data-cy="invitations-list"]').should('not.exist')
    })
  })

  it.only('should allow owners to resend an invitation', () => {
    cy.create({
      model: 'App\\Models\\TeamInvitation',
      attributes: {
        team_id: teamId,
      },
    }).then(() => {
      cy.reload()
      cy.get('@switchViewModeButton').click()

      cy.get('[data-cy="invitations-list"]').within(() => {
        cy.get('[data-cy="resend-invitation-button"]').click()
      })

      cy.get('[data-cy="success-notification"]').should('contain', 'Invitation resent')
    })
  })
})
