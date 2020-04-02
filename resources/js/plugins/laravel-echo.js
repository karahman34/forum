import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: process.env.MIX_PUSHER_APP_KEY,
  cluster: 'ap1',
  forceTLS: true,
  authEndpoint: '/broadcasting/auth',
});
