// Use Axios
window.axios = require('axios');

// Use Bulma Toast
require('./plugins/bulmaToast');

// Require global config
require('./_global');

// Define Vue
window.Vue = require('vue');

// Images
Vue.component('image-modal', require('./components/imageModal.vue').default);

// Posts
Vue.component('post', require('./posts/post.vue').default);
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
