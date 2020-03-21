// Use Axios
window.axios = require('axios');

// Use bulma toast
bulmaToast = require('bulma-toast').toast;
window.toast = options => {
  const defaultOptions = {
    closeOnClick: false,
    pauseOnHover: true,
    dismissible: true,
    position: 'bottom-left',
    duration: '3000',
  };

  const finalOptions = { ...defaultOptions, ...options };

  bulmaToast(finalOptions);
};

// Require global config
require('./_global');

// Define Vue
window.Vue = require('vue');

// Posts
Vue.component('post-form', require('./posts/form.vue').default);
Vue.component('post-filter', require('./posts/postFilter.vue').default);
Vue.component('post-menus', require('./posts/menus.vue').default);

// Comments
Vue.component('comment', require('./comments/comment.vue').default);
Vue.component('comment-section', require('./comments/section.vue').default);
Vue.component('comment-filter', require('./comments/filter.vue').default);
Vue.component('comment-create', require('./comments/create.vue').default);
Vue.component('comment-edit', require('./comments/edit.vue').default);

// Create vue instance
const app = new Vue({
  el: '#app',
});
