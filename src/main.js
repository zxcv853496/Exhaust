import Vue from 'vue'
import App from './App.vue'
import router from './router'
import DataValid from './common/data_valid'
import axios from 'axios'
import VueAxios from 'vue-axios'

Vue.config.productionTip = false
Vue.mixin(DataValid)
Vue.use(VueAxios, axios)

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')