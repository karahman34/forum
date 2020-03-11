// Use Axios
window.axios = require('axios');

// Require global config
require('./_global');

// Define Vue
window.Vue = require('vue');

// Posts
Vue.component('post-form', require('./posts/form.vue').default);

// Comments
Vue.component('comment-list', require('./comments/list.vue').default);
Vue.component('comment-create', require('./comments/create.vue').default);
Vue.component('comment-edit', require('./comments/edit.vue').default);

// Create vue instance
const app = new Vue({
  el: '#app',
});
