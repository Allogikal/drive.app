import '@/assets/css/app.css'
import '@/assets/css/common.css'
import '@/assets/css/main.css'
import '@/assets/css/reset.css'
import 'sweetalert2/dist/sweetalert2.min.css'

import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from 'ziggy-js'
import { createApp, h } from 'vue'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import HeaderMain from '@/components/HeaderMain.vue'

createInertiaApp({
    resolve: (name) =>
        resolvePageComponent(`./pages/${name}.vue`, import.meta.glob('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component('Header', HeaderMain)
            .mount(el)
    }
})
