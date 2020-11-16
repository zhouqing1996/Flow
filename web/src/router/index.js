import Vue from 'vue'
import Router from 'vue-router'


//404错误
import NotFound from '../pages/404'
Vue.use(Router)
import My from '../pages/My'
import ChangePwd from '../pages/Changepwd'
import Register from '../pages/register'
import Forget from '../pages/forget'
import Newpass from '../pages/newpass'
import Login from '../pages/login'
import Index from '../pages/Home'
export default new Router({
  base:'/Flow/',
  mode:'hash',
  hash:true,
  routes: [
    {
      path:'/',
      name:'Index',
      meta:{
        isLogin:true
      },
      component:Index

    },
    {
      path:'/my',
      name:'my',
      meta:{
        isLogin:true
      },
      component:My
    },
    {
      path:'/changepwd',
      name:'changepwd',
      meta:{
        isLogin:true
      },
      component:ChangePwd
    },
    {
      //注册
      path: '/register',
      name: 'Register',
      meta:{
        isLogin:false,
      },
      component: Register
    },
    {
      //忘记密码
      path:'/forget',
      name:'Forget',
      meta:{
        isLogin:false,
      },
      component:Forget
    },
    {
      //忘记密码
      path:'/newpass',
      name:'Newpass',
      meta:{
        isLogin:false,
      },
      component:Newpass
    },
    {
      //登录
      path:'/login',
      name:'Login',
      meta:{
        isLogin:false,
      },
      component:Login
    },
    {
      path:'*',
      name:'notfound',
      meta:{
        isLogin:false
      },
      component:NotFound
    },
  ]
})
