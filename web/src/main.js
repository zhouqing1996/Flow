// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import ElementUI from 'element-ui'
import Vuex from 'vuex'
import 'element-ui/lib/theme-chalk/index.css';
import Axios from 'axios'
import store from './store/index'
//编辑器
import 'font-awesome/css/font-awesome.min.css'
import initVueHtml5 from "./common/initVueHtml5.js";
//图表
import echarts from 'echarts'
Vue.prototype.$echarts = echarts

Vue.prototype.$http = Axios
Axios.interceptors.request.use(function (config) {
  // config.headers="Access-Control-Allow-Origin: *";
  config.url=process.env.API_HOST+config.url
  return config
})
router.beforeEach((to,from,next)=> {
  let user = store.getters.getsName
  console.log(user)
  if (to.meta.isLogin == true) {
    if (!user) {
      //获取不到登录信息
      next({path: '/login'})
    } else {
      return next()
    }
  } else {
    return next();
  }
})
Vue.use(ElementUI)
Vue.use(Vuex)
Vue.config.productionTip = false
Vue.use(initVueHtml5)

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})
