import { defineConfig } from 'vitepress'

export default defineConfig({
  title: 'Blast',
  description: 'Privacy focused, open source URL shortener.',
  outDir: '../public/docs',
  base: '/docs/',

  themeConfig: {
    nav: [{ text: 'Blast', link: 'https://blst.to' }],

    sidebar: [
      {
        text: 'Getting Started',
        items: [{ text: 'Introduction', link: '/introduction' }],
      },
      {
        text: 'Teams',
        items: [
          { text: 'Teams', link: '/teams' },
          { text: 'Team Invitations', link: '/team-invitations' },
          { text: 'Team Members', link: '/team-members' },
        ],
      },
      {
        text: 'Links',
        items: [{ text: 'Links', link: '/links' }],
      },
    ],

    search: {
      provider: 'local',
      options: {
        placeholder: 'Search Blast Docs...',
      },
    },
  },

  vite: {
    server: {
      host: true,
      fs: {
        allow: ['../..'],
      },
    },
  },
})
