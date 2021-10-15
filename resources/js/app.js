require('./bootstrap');

require('alpinejs');

import Vue from 'vue';
import chartcomponent from './components/chart.vue'

const app = new Vue({
  el: '#app',
  components: {
    chartcomponent,
  }
})