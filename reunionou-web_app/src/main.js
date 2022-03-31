import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
//import 'bootstrap/dist/css/bootstrap.css'
//import 'bootstrap-vue/dist/bootstrap-vue.css'
import './assets/custom.scss'

Vue.prototype.$api = axios.create({
  baseURL : 'http://docketu.iutnc.univ-lorraine.fr:62640/',
  params : {},
  headers : {}
});

Vue.prototype.$api.interceptors.request.use(function(config){
  console.log(config)
  if(store.state.token){
    config.params.token = store.state.token
  }
  return config;
})

Vue.prototype.$api.interceptors.response.use(function(response){
  return response;
},function(error){
  if(error.response.data.message) {
    alert(error.response.data.message);
  }
})

Vue.config.productionTip = false

//Chargement global du composant Navigation
Vue.component('Header', ()=>import('@/components/Header.vue'));

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
