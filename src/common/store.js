import Vue from 'vue';

let state = {
    loading: false,
    dialog: {
        msg: "",
        status: true
      }
}
Vue.observable(state)
// let store =Vue.observable({loading:false});

function setLoading(value){
    state.loading = value
}

function setDialog(value , msg){
    state.dialog.status = value
    state.dialog.msg = msg
}

export default{
    state,
    setLoading,
    setDialog,
}

// export let store =Vue.observable({loading:true});
// export let mutations={
//     setLoading(){
//         store.loading =! store.loading;
//     },
// }