import './bootstrap';
import 'toastr/build/toastr.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css'
import toastr from 'toastr';
window.toastr = toastr;

import { createInertiaApp, usePage  } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h, watch  } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mount(el);

        // Flash messages with toastr
         const page = usePage();
        watch(
            () => page.props.flash,
            (flash) => {
                if (!flash) return;
                if (flash.success) toastr.success(flash.success, 'Success');
                if (flash.error) toastr.error(flash.error, 'Error');
                if (flash.info) toastr.info(flash.info, 'Info');
                if (flash.warning) toastr.warning(flash.warning, 'Warning');
            },
            { immediate: true }
        );

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});

