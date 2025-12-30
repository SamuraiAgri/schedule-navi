/**
 * Schedule-Navi メインアプリケーションエントリーポイント
 * Inertia.js + Vue 3 セットアップ
 */
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import './bootstrap';

createInertiaApp({
    title: (title) => title ? `${title} - Schedule-Navi` : 'Schedule-Navi',
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue')
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#3B82F6',
    },
});
