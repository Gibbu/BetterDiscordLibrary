const { tailwindExtractor } = require("tailwindcss/lib/lib/purgeUnusedStyles");
const colours = require('tailwindcss/colors');

module.exports = {
  mode: 'jit',
  purge: {
		content: [
			'./resources/**/*.svelte',
      './resources/**/*.blade.php'
		],
		options: {
			defaultExtractor: (content) => [
				...tailwindExtractor(content),
				...[...content.matchAll(/(?:class:)*([\w\d-/:%.]+)/gm)].map(([_match, group, ..._rest]) => group),
			],
			keyframes: true,
		},
	},
  darkMode: 'class',
  theme: {
    fontFamily: {
      'display': ['Montserrat'],
      'body': ['Inter', 'Open Sans', 'Roboto', 'sans-serif']
    },
    extend: {
      colors: {
        teal: colours.teal,
        gray: {
          '950': '#101623'
        }
      },
      typography: (theme) => ({
        DEFAULT: {
          css: {
            code: {
              backgroundColor: theme('colors.gray.300'),
              padding: '0.125rem 0.375rem',
              borderRadius: theme('spacing.1'),
              '&:before': {
                content: 'none !important'
              },
              '&:after': {
                content: 'none !important'
              }
            }
          }
        },
        light: {
          css: [
            {
              color: theme('colors.gray.300'),
              '[class~="lead"]': {
                color: theme('colors.gray.300'),
              },
              a: {
                color: theme('colors.white'),
              },
              strong: {
                color: theme('colors.white'),
              },
              'ol > li::before': {
                color: theme('colors.gray.400'),
              },
              'ul > li::before': {
                backgroundColor: theme('colors.gray.600'),
              },
              hr: {
                borderColor: theme('colors.gray.200'),
              },
              blockquote: {
                color: theme('colors.gray.200'),
                borderLeftColor: theme('colors.gray.600'),
              },
              h1: {
                color: theme('colors.white'),
              },
              h2: {
                color: theme('colors.white'),
              },
              h3: {
                color: theme('colors.white'),
              },
              h4: {
                color: theme('colors.white'),
              },
              'figure figcaption': {
                color: theme('colors.gray.400'),
              },
              code: {
                color: theme('colors.white'),
                backgroundColor: theme('colors.gray.800'),
                borderRadius: theme('rounded')
              },
              'a code': {
                color: theme('colors.white'),
              },
              pre: {
                color: theme('colors.gray.200'),
                backgroundColor: theme('colors.gray.800'),
              },
              thead: {
                color: theme('colors.white'),
                borderBottomColor: theme('colors.gray.400'),
              },
              'tbody tr': {
                borderBottomColor: theme('colors.gray.600'),
              },
            },
          ],
        },
      }),
    },
  },
  variants: {
    extend: {
      typography: ['dark']
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/aspect-ratio'),
    require('@tailwindcss/typography'),
  ],
}
