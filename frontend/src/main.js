import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { createI18n } from 'vue-i18n'

import App from './App.vue'
import router from './router'

import it from './locales/it.json'

const i18n = createI18n({
  locale: 'it',
  fallbackLocale: 'it',
  messages: {
    it
  }
})

import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(i18n)
app.use(Toast)

app.mount('#app')
