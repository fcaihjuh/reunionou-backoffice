import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import axios from "axios";

import { Outils } from "./mixins/Outils.js";
Vue.mixin(Outils);

Vue.prototype.$bus = new Vue();
/*
Vue.prototype.$api = new axios.create({
    baseURL: "https://allweb.fun/coop/api/",
    params: {},
    headers: { Authorization: "f1738766c03ec361cdf00c5efc24c1c58dfe0e10" },
});
*/

Vue.prototype.$api = axios.create({
    baseURL : 'http://docketu.iutnc.univ-lorraine.fr:62640/',
    params : {},
    headers : {}
  });

Vue.prototype.$api.interceptors.request.use(function(config) {
    if (store.state.token) {
        config.params.token = store.state.token; //le token de connexion vers le compte utilisateur
    }
    return config;
});
Vue.config.productionTip = false;

Vue.component("Header", () =>
    import ("@/components/Header.vue"));

new Vue({
    router,
    store,
    render: (h) => h(App),
}).$mount("#app");

//https://eu.ui-avatars.com/