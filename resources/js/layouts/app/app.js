// Use Axios
window.axios = require('axios');

// Require global config
require('./_global');

// Define Vue
window.Vue = require('vue');

// Comment List
Vue.component('comment-list', require('./comments/list.vue').default);

// Create vue instance
const app = new Vue({
  el: '#app',
});
