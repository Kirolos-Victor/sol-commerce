require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

import { Inertia } from '@inertiajs/inertia'
Inertia.on('navigate', (event) => {
    console.log(event);

    dataLayer.push({
        'url': event.detail.page.url,
        'event': 'pageview'
    });

    // identify
    if (event.detail.page.props.auth && event.detail.page.props.auth.user) {
        dataLayer.push({'klav_identify': {
            '$email': event.detail.page.props.auth.user.email,
            '$first_name': event.detail.page.props.auth.user.first_name,
            '$last_name': event.detail.page.props.auth.user.last_name,
        }});

        dataLayer.push({'event': 'klaviyo_identify'});
    }
})

const el = document.getElementById('app');

import mitt from 'mitt'
const emitter = mitt()

import SwiperCore, { Navigation, Pagination, Scrollbar, A11y } from 'swiper';
SwiperCore.use([Navigation, Pagination, Scrollbar, A11y]);
import 'swiper/swiper-bundle.min.css';

const app = createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
})
    .mixin({ methods: { route } })
    .use(InertiaPlugin);

// custom use statements here

app.mount(el);

InertiaProgress.init({ color: '#4B5563' });
