/**
 * Initialise the Laravel Cypress helper.
 */
Cypress.Laravel = {
  routes: {},

  route: (name, parameters = {}) => {
    assert(Object.hasOwn(Cypress.Laravel.routes, name), `Laravel route "${name}" does not exist.`)

    return ((uri) => {
      Object.keys(parameters).forEach((parameter) => {
        uri = uri.replace(new RegExp(`{${parameter}}`), parameters[parameter])
      })

      return uri
    })(Cypress.Laravel.routes[name].uri)
  },
}

/**
 * Create a new user and log them in.
 */
Cypress.Commands.add('login', (attributes) => {
  return cy
    .csrfToken()
    .then((token) => {
      return cy.request({
        method: 'POST',
        url: '/__cypress__/login',
        body: { ...attributes, _token: token },
        log: false,
      })
    })
    .then(({ body }) => {
      Cypress.Laravel.currentUser = body

      Cypress.log({
        name: 'login',
        message: JSON.stringify(body),
        consoleProps: () => ({ user: body }),
      })
    })
    .its('body', { log: false })
})

/**
 * Fetch the currently authenticated user object.
 */
Cypress.Commands.add('currentUser', () => {
  return cy.csrfToken().then((token) => {
    return cy
      .request({
        method: 'POST',
        url: '/__cypress__/current-user',
        body: { _token: token },
        log: false,
      })
      .then((response) => {
        if (!response.body) {
          cy.log('No authenticated user found.')
        }

        Cypress.Laravel.currentUser = response.body

        return response.body
      })
  })
})

/**
 * Logout the current user.
 */
Cypress.Commands.add('logout', () => {
  return cy
    .csrfToken()
    .then((token) => {
      return cy.request({
        method: 'POST',
        url: '/__cypress__/logout',
        body: { _token: token },
        log: false,
      })
    })
    .then(() => {
      Cypress.log({ name: 'logout', message: '' })
    })
})

/**
 * Fetch a CSRF token.
 */
Cypress.Commands.add('csrfToken', () => {
  return cy
    .request({
      method: 'GET',
      url: '/__cypress__/csrf_token',
      log: false,
    })
    .its('body', { log: false })
})

/**
 * Fetch and store all named routes.
 */
Cypress.Commands.add('refreshRoutes', () => {
  return cy.csrfToken().then((token) => {
    return cy
      .request({
        method: 'POST',
        url: '/__cypress__/routes',
        body: { _token: token },
        log: false,
      })
      .its('body', { log: false })
      .then((routes) => {
        cy.writeFile(Cypress.config().supportFolder + '/routes.json', routes, {
          log: false,
        })

        Cypress.Laravel.routes = routes
      })
  })
})

/**
 * Create a new Eloquent factory.
 */
Cypress.Commands.add('create', (request) => {
  return cy
    .csrfToken()
    .then((token) => {
      return cy.request({
        method: 'POST',
        url: '/__cypress__/factory',
        body: { ...request, _token: token },
        log: false,
      })
    })
    .then((response) => {
      Cypress.log({
        name: 'create',
        message: request.model + ` (${request.count || 1} times)`,
        consoleProps: () => ({ model: response.body }),
      })
    })
    .its('body', { log: false })
})

/**
 * Visit the given URL or route.
 */
Cypress.Commands.overwrite('visit', (originalFn, options) => {
  if (options.route) {
    return originalFn({
      url: Cypress.Laravel.route(options.route, options.parameters || {}),
      ...options,
    })
  }

  return originalFn(options)
})

/**
 * Refresh the database state.
 */
Cypress.Commands.add('refreshDatabase', (options = {}) => {
  return cy.artisan('migrate:fresh', {}, options)
})

/**
 * Seed the database.
 */
Cypress.Commands.add('seed', (seederClass = '') => {
  const options: { '--class'?: string } = {}

  if (seederClass) {
    options['--class'] = seederClass
  }

  return cy.artisan('db:seed', {}, options)
})

/**
 * Trigger an Artisan command.
 */
Cypress.Commands.add('artisan', (command, parameters = {}, options = { log: true }) => {
  if (options.log) {
    Cypress.log({
      name: 'artisan',
      message: (() => {
        let message = command

        for (const key in parameters) {
          message += ` ${key}="${parameters[key]}"`
        }

        return message
      })(),
      consoleProps: () => ({ command, parameters }),
    })
  }

  return cy.csrfToken().then((token) => {
    return cy.request({
      method: 'POST',
      url: '/__cypress__/artisan',
      body: { command: command, parameters: parameters, _token: token },
      log: false,
    })
  })
})

/**
 * Execute arbitrary PHP.
 */
Cypress.Commands.add('php', (command) => {
  return cy
    .csrfToken()
    .then((token) => {
      return cy.request({
        method: 'POST',
        url: '/__cypress__/run-php',
        body: { command: command, _token: token },
        log: false,
      })
    })
    .then((response) => {
      Cypress.log({
        name: 'php',
        message: command,
        consoleProps: () => ({ result: response.body.result }),
      })
    })
    .its('body.result', { log: false })
})
