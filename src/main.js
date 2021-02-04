import Vue from 'vue'
import App from './App.vue'
import router from './router'
import DataValid from './common/data_valid'
import CommonMethod from './common/common_methods'
import GaMethod from './common/ga_methods'
import axios from 'axios'
import VueAxios from 'vue-axios'
import VueGtm from 'vue-gtm'
import store from './store'

Vue.config.productionTip = false
Vue.mixin(DataValid)
Vue.mixin(GaMethod)
Vue.mixin(CommonMethod)

Vue.use(VueAxios, axios)
Vue.use(VueGtm, {
  id: "GTM-KD4W8HB",
  enabled: true,
  debug: true,
  trackOnNextTick: false
})

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')