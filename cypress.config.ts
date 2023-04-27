import { defineConfig } from 'cypress'
import * as fs from 'fs'

export default defineConfig({
  video: false,
  videosFolder: 'tests/cypress/videos',
  screenshotsFolder: 'tests/cypress/screenshots',
  fixturesFolder: 'tests/cypress/fixture',
  downloadsFolder: 'tests/cypress/downloads',
  e2e: {
    setupNodeEvents(on: Cypress.PluginEvents, config: Cypress.PluginConfigOptions): void {
      on('before:run', () => {
        if (fs.existsSync('.env.cypress') && config.env.CYPRESS_ENV !== 'ci') {
          fs.renameSync('.env', '.env.backup')
          fs.renameSync('.env.cypress', '.env')
          setAppKey('.env.backup', '.env')
        }
      })

      on('after:run', () => {
        if (fs.existsSync('.env.backup') && config.env.CYPRESS_ENV !== 'ci') {
          fs.renameSync('.env', '.env.cypress')
          fs.renameSync('.env.backup', '.env')
          setAppKey('.env.example', '.env.cypress')
        }
      })
    },
    baseUrl: 'http://localhost',
    specPattern: 'tests/cypress/e2e/**/*.cy.{js,jsx,ts,tsx}',
    supportFile: 'tests/cypress/support/e2e.ts',
  },
})

function setAppKey(source: string, target: string): void {
  const sourceContent: string = fs.readFileSync(source, 'utf8')
  const sourceLines: string[] = sourceContent.split('\n')
  const sourceAppKeyLine: string | undefined = sourceLines.find((line: string) => line.startsWith('APP_KEY='))
  const sourceAppKey: string = sourceAppKeyLine?.split('=')[1] || ''

  const targetContent: string = fs.readFileSync(target, 'utf8')
  const targetLines: string[] = targetContent.split('\n')
  const targetAppKeyIndex: number = targetLines.findIndex((line: string) => line.startsWith('APP_KEY='))

  if (targetAppKeyIndex !== -1) {
    targetLines[targetAppKeyIndex] = `APP_KEY=${sourceAppKey}`
  } else {
    targetLines.push(`APP_KEY=${sourceAppKey}`)
  }

  fs.writeFileSync(target, targetLines.join('\n'))
}
