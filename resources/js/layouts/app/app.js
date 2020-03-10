// Use Axios
window.axios = require('axios');

// Require global config
require('./_global');

// Define Vue
window.Vue = require('vue');

const app = new Vue({
  el: '#app',
});
