import { createI18n } from 'vue-i18n'
import { reactive } from 'vue'

export default function setupI18n(app, pageProps) {
    const messages = pageProps.translations || {}
    const locale = pageProps.locale || 'en'

    const i18n = createI18n({
        legacy: false,
        locale: locale,
        fallbackLocale: 'en',
        messages: messages,
    })

    app.use(i18n)
}
