window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import { setPusherClient } from 'react-pusher';
import Pusher from 'pusher-js';

const pusherClient = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    disableStats: true,
    wsHost: process.env.MIX_PUSHER_APP_WS_HOST,
    wsPort: process.env.MIX_PUSHER_APP_WS_PORT,
});

setPusherClient(pusherClient);
