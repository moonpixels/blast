const defaultTheme = require('tailwindcss/defaultTheme')

/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',

  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
    './docs/.vitepress/**/*.{html,js,vue,ts}',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
      },
      typography: (theme) => ({
        invert: {
          css: {
            '--tw-prose-body': theme('colors.zinc.400'),
            '--tw-prose-headings': theme('colors.zinc.100'),
            '--tw-prose-lead': theme('colors.zinc.400'),
            '--tw-prose-code': theme('colors.zinc.100'),
            '--tw-prose-code-bg': theme('colors.white / 0.05'),
            '--tw-prose-code-ring': theme('colors.white / 0.1'),

            // Links
            a: {
              '&:hover': {
                color: theme('colors.zinc.100'),
              },
            },
          },
        },
        DEFAULT: {
          css: {
            '--tw-prose-body': theme('colors.zinc.600'),
            '--tw-prose-headings': theme('colors.zinc.900'),
            '--tw-prose-lead': theme('colors.zinc.600'),
            '--tw-prose-code': theme('colors.zinc.900'),
            '--tw-prose-code-bg': theme('colors.zinc.900 / 0.05'),
            '--tw-prose-code-ring': theme('colors.zinc.900 / 0.1'),

            // Base
            color: 'var(--tw-prose-body)',
            fontSize: theme('fontSize.sm')[0],
            lineHeight: theme('lineHeight.7'),

            // Text
            p: {
              marginTop: theme('spacing.6'),
              marginBottom: theme('spacing.6'),
            },
            '[class~="lead"]': {
              fontSize: theme('fontSize.base')[0],
              ...theme('fontSize.base')[1],
            },

            // Headings
            h1: {
              color: 'var(--tw-prose-headings)',
              fontWeight: theme('fontWeight.semibold'),
              fontSize: theme('fontSize.xl')[0],
              ...theme('fontSize.xl')[1],
              marginBottom: theme('spacing.2'),
            },
            h2: {
              color: 'var(--tw-prose-headings)',
              fontWeight: theme('fontWeight.medium'),
              fontSize: theme('fontSize.lg')[0],
              ...theme('fontSize.lg')[1],
              marginTop: theme('spacing.16'),
              marginBottom: theme('spacing.2'),
            },
            h3: {
              color: 'var(--tw-prose-headings)',
              fontWeight: theme('fontWeight.medium'),
              fontSize: theme('fontSize.base')[0],
              ...theme('fontSize.base')[1],
              marginTop: theme('spacing.10'),
              marginBottom: theme('spacing.2'),
            },

            // Links
            a: {
              textUnderlineOffset: '2px',
              textDecorationLine: 'underline',
              textDecorationStyle: 'dashed',
              color: 'inherit',
              '&:hover': {
                color: theme('colors.zinc.900'),
              },
            },

            // Inline elements
            code: {
              color: 'var(--tw-prose-code)',
              borderRadius: theme('borderRadius.DEFAULT'),
              paddingTop: theme('padding.1'),
              paddingRight: theme('padding[1.5]'),
              paddingBottom: theme('padding.1'),
              paddingLeft: theme('padding[1.5]'),
              boxShadow: 'inset 0 0 0 1px var(--tw-prose-code-ring)',
              backgroundColor: 'var(--tw-prose-code-bg)',
              fontSize: theme('fontSize.2xs'),
              '&::before': {
                content: '"" !important',
              },
              '&::after': {
                content: '"" !important',
              },
            },

            // Overrides
            ':is(h1, h2, h3) + *': {
              marginTop: '0',
            },
            '> :first-child': {
              marginTop: '0 !important',
            },
            '> :last-child': {
              marginBottom: '0 !important',
            },
          },
        },
      }),
    },
  },

  plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
}
