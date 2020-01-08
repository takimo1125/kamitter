
import './bootstrap'
import Vue from 'vue'
import router from './router'
import store from './store'

import App from './app.vue'
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css'
import locale from 'element-ui/lib/locale/lang/ja';

Vue.use(ElementUI, { locale })
/**
 * 一番最初の初期描画の時に、ユーザ認証とTwitterUser認証を行う
 */
const createApp = async () => {
    await store.dispatch('auth/currentUser')
    await store.dispatch('auth/currentTwitterUser')

    new Vue({
        el: '#app',
        router,
        store,
        components: {App},
        template: '<App />'
    })
}

createApp()
