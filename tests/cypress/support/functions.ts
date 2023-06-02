import { User } from '@/types'
import Chainable = Cypress.Chainable

/**
 * Get the OTP code for the given user.
 */
export function getOtpCodeForUser(user: User): Chainable<string> {
  return cy
    .php(
      `app(PragmaRX\\Google2FA\\Google2FA::class)->getCurrentOtp(Illuminate\\Support\\Facades\\Crypt::decrypt(App\\Models\\User::find('${user.id}')->two_factor_secret));`
    )
    .then((response) => {
      expect(response).to.be.a('string')
      return response
    })
}

/**
 * Create a new user.
 */
export function createUser(attributes: Partial<User> = {}, state: string[] = []): Chainable<User> {
  if (Object.keys(attributes).length === 0) {
    attributes = {
      email: 'john.doe@example.com',
    }
  }

  return cy
    .create({
      model: 'App\\Models\\User',
      attributes,
      state,
    })
    .then((model) => {
      return model
    })
}
