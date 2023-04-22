export default {
  // Global page headers: https://go.nuxtjs.dev/config-head
  mode: 'universal',
  head: {
    title: "tf-sample",
    htmlAttrs: {
      lang: "en"
    },
    bodyAttrs: {
      //class: 'bg-gray-100'
    },
    script:[
      { src: 'https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.4/flowbite.min.js' }
    ],
    meta: [
      { charset: "utf-8" },
      { name: "viewport", content: "width=device-width, initial-scale=1" },
      { hid: "description", name: "description", content: "" },
      { name: "format-detection", content: "telephone=no" }
    ],
    link: [
      { rel: "icon", type: "image/x-icon", href: "/favicon.ico" },
      {
        rel: 'stylesheet',
        href: 'https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap'
      }
    ]
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    '@/assets/css/main.css',
    'aos/dist/aos.css'
  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: ['~/plugins/smooth-scroll.js', '~/plugins/mdi.js'],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    // https://go.nuxtjs.dev/typescript
    "@nuxt/typescript-build",
    '@nuxtjs/eslint-module',
    // https://go.nuxtjs.dev/tailwindcss
    "@nuxtjs/tailwindcss",
    '@nuxtclub/feathericons'
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: ["@nuxtjs/axios",'@nuxtjs/auth-next',"@nuxtjs/device"],

  auth: {
    strategies: {
      cookie: {
        endpoints: {
          csrf: {
            url: '/sanctum/csrf-cookie'
          },
          login: {
            url: '/login'
          },
          logout: {
            url: '/logout'
          },
          user: {
            url: '/user'
          }
        },
        user: {
          property: 'data'
        },
      }
    },

    redirect: {
      login: '/login',
      logout: '/login',
      home: '/'
    },

    plugins: ['~/plugins/axios'],
  },

  axios: {
    baseURL: process.env.API_BASE_URL,// Used as fallback if no runtime config is provided
    credentials: true,
    proxy:true
  },

  proxy: {
    "/api": "https://127.0.0.1:8000"
  },

  publicRuntimeConfig: {
    axios: {
      browserBaseURL: process.env.API_BASE_URL
    }
  },

  privateRuntimeConfig: {
    axios: {
      baseURL: process.env.API_SSR_URL
    }
  },

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
    transpile: [
      'defu',
      //'@headlessui/vue'
    ],
    postcss: {
      postcssOptions: {
        plugins: {
          tailwindcss: {},
          autoprefixer: {},
        },
      },
    },
  }
};
