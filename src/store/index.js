import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    loading: false,
    dialog: {
      msg: "",
      status: false
    }
  },
  mutations: {
    SetLoading(state, action) {
      state.loading = action
    },
    SetDialog(state, [action, msg]) {
      state.dialog.msg = msg
      state.dialog.status = action
    },
  },
  actions: {},
  modules: {}
})