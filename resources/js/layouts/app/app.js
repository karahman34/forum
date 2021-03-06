// Use Axios
window.axios = require('axios');

// Use Bulma Toast
require('./plugins/bulmaToast');

// Use Laravel Echo
require('../../plugins/laravel-echo');

// Require global config
require('./_global');

// Define Vue
window.Vue = require('vue');

// Search
Vue.component(
  'search-navbar',
  require('./components/search/search-navbar.vue').default
);
Vue.component(
  'search-users-result',
  require('./components/search/users-result.vue').default
);
Vue.component(
  'search-posts-result',
  require('./components/search/posts-result.vue').default
);

// Images
Vue.component('image-modal', require('./components/imageModal.vue').default);

// Logout
Vue.component('logout', require('./components/logout.vue').default);

// Posts
Vue.component('post', require('./posts/post.vue').default);
Vue.component('post-form', require('./posts/form.vue').default);
Vue.component('post-filter', require('./posts/postFilter.vue').default);
Vue.component('post-menus', require('./posts/menus.vue').default);

// Comments
Vue.component('comment-section', require('./comments/section.vue').default);
Vue.component('comment-filter', require('./comments/filter.vue').default);
Vue.component('comment-create', require('./comments/create.vue').default);
Vue.component('comment-edit', require('./comments/edit.vue').default);

// Notifications
Vue.component(
  'notification-count',
  require('./notifications/navbar-count.vue').default
);
Vue.component(
  'notifications',
  require('./notifications/notifications.vue').default
);

// Create vue instance
const app = new Vue({
  el: '#app',
});
