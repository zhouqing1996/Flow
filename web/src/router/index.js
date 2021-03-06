import Vue from 'vue'
import Router from 'vue-router'
//404错误
import NotFound from '../pages/404'

import My from '../pages/My'
import ChangePwd from '../pages/Changepwd'
import Register from '../pages/register'
import Forget from '../pages/forget'
import Newpass from '../pages/newpass'
import Login from '../pages/login'
import Index from '../pages/Home'
//管理员
import adminHome from "../pages/admin/adminHome";
import adminIndex from "../pages/admin/adminIndex";
import adminUser from '../pages/admin/User'
import adminLookApp from '../pages/admin/application/LookApp'
import adminLookApp2 from '../pages/admin/application/LookApp2'


//普通用户
import userHome from "../pages/ordinary/userHome";
import userIndex from "../pages/ordinary/userIndex";
import userCreateApp from '../pages/ordinary/application/CreateApp'
import userCreateApp2 from '../pages/ordinary/application/CreateApp2'
import userLookApp from '../pages/ordinary/application/LookApp'
import userLookApp2 from '../pages/ordinary/application/LookApp2'
import userPendManage from '../pages/ordinary/manage/pendManage'
import userFinishManage from '../pages/ordinary/manage/finishManage'
import userLookManage from '../pages/ordinary/manage/LookManage'
import userFormCreate from '../pages/ordinary/application/FormCreate'
Vue.use(Router)
export default new Router({
  base:'/Flow/',
  mode:'hash',
  hash:true,
  routes: [
    {
      path: '/admin',
      name:'admin',
      meta: {
        isLogin: true
      },
      component: adminHome,
      children:[
        {
          path:'/admin/index',
          name:'adminIndex',
          meta:{
            isLogin:true
          },
          component:adminIndex
        },
        {
          path: '/admin/user',
          name:'adminUser',
          meta: {
            isLogin: true
          },
          component: adminUser
        },
        {
          path: '/admin/my',
          name: 'adminMy',
          meta: {
            isLogin: true
          },
          component:My
        },
        {
          path:'/admin/application/look',
          name:'adminLook',
          meta:{
            isLogin:true
          },
          component:adminLookApp
        },
        {
          path:'/admin/application/lookapp',
          name:'adminLook2',
          meta:{
            isLogin:true
          },
          component:adminLookApp2
        },
      ]
    },
    {
      path: '/user',
      name: 'User',
      meta: {
        isLogin: true
      },
      component: userHome,
      children: [
        {
          path: '/user/index',
          name: 'userIndex',
          meta: {
            isLogin: true
          },
          component: userIndex
        },
        {
          path: '/user/my',
          name: 'userMy',
          meta: {
            isLogin: true
          },
          component: My
        },
        {
          //新建申请
          path: '/user/application/create',
          name:'userCreateApp',
          meta: {
            isLogin: true
          },
          component: userCreateApp
        },
        {
          path:'/user/application/formcreate',
          name:'suerFormCreate',
          meta:{
            isLogin:true
          },
          component:userFormCreate
        },
        {
          //申请表内容
          path: '/user/application/created',
          name:'userCreateApp2',
          meta: {
            isLogin: true
          },
          component: userCreateApp2
        },
        {
          path:'/user/application/look',
          name: 'userLookApp',
          meta: {
            isLogin: true
          },
          component: userLookApp
        },
        {
          path:'/user/application/lookapp',
          name:'userLookApp2',
          meta:{
            isLogin:true
          },
          component:userLookApp2
        },
        {
        //  待审批
          path: '/user/manage/pendmanage',
          name:'userPendManage',
          meta: {
            isLogin: true
          },
          component: userPendManage
        },
        {
        //  已审批
          path: '/user/manage/finishmanage',
          name: 'userFinishManage',
          meta: {
            isLogin: true
          },
          component: userFinishManage
        },
        {
        //  审批时查看申请表
          path:'/user/manage/lookmanage',
          name:'userLookManage',
          meta:{
            isLogin:true
          },
          component:userLookManage
        }
      ]
    },
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
