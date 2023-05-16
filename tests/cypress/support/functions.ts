import { User } from '@/types'
import Chainable = Cypress.Chainable

export function getOtpCodeForUser(user: User): Chainable<string> {
  return cy.php(`app(PragmaRX\\Google2FA\\Google2FA::class)->getCurrentOtp(Illuminate\\Support\\Facades\\Crypt::decrypt(App\\Models\\User::find('${user.id}')->two_factor_secret));`)
    .then((response) => {
      expect(response).to.be.a('string')
      return response
    })
}