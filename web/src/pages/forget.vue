<template>
  <div class="page">
    <div class="loginwarrp">
      <div class="logo">文件流转系统(找回密码)</div>
      <div class="login_form">
        <li class="login-item" >
          <span style="font-size: 20px;color: #0ea0db;" class="el-icon-user">用户工号：</span>
          <input type="text" v-model="forgetForm.username" auto-complete="off" placeholder="账号" class="login_input">
          <span class="error"></span>
        </li>
        <li class="login-item" >
          <span style="font-size: 20px;color: #0ea0db;" class="el-icon-message"> 找回邮箱：</span>
          <input type="text" v-model="forgetForm.email" auto-complete="off" placeholder="账号" class="login_input">
          <span class="error"></span>
        </li>
        <div class="clearfix">
          <li class="login-sub">
            <input type="button" value="确认" v-on:click="forget">
          </li>
          <br>
          <p class="other">
            <router-link to="/login">直接登录</router-link>
            <router-link to="/register">立即注册</router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
  export default {
    name: "forget",
    data() {
      return {
        forgetForm: {
          username: '',
          email:''
        },
        responseResult: []
      }
    },
    methods: {
      forget() {
        let that =this
        var EmailReg = new RegExp(/[0-9a-zA-Z_.-]+[@][0-9a-zA-Z_.-]+([.][a-zA-Z]+){1,2}/)
      if(that.forgetForm.username=='')
        {
          that.$alert('用户名不能为空或者空格！', '警告', {
            confirmButtonText: '确定',})
        }
      else if(!EmailReg.test(that.forgetForm.email))
      {
        that.$alert('邮箱格式不对！', '警告', {
          confirmButtonText: '确定',})
      }
      else {

        that.$http.post('/home/index/forget',{
            username:that.forgetForm.username,
            email:that.forgetForm.email
          }).then(res=>{
            console.log(res.data)
          // alert(res.data.message)
          if(res.data.data==0)
          {
            that.$alert(res.data.message, '警告', {
              confirmButtonText: '确定',})
          }
          else
          {
            that.$alert(res.data.message, '提示', {
              confirmButtonText: '确定',})
          }
            // if(res.data.message=="修改密码成功")
            // {
            //   alert('修改密码成功')
            //   that.$store.dispatch('slogout')
            //   that.$router.push({
            //     path:'/login',
            //     params:{
            //       username:this.forgetForm.username
            //     }
            //   })
            // }
            // else {
            //   alert(res.data.message)
            // }
          }).catch(function (error) {
            console.log(error)
          })
        }

      },
    },
    created:function () {
      this.forgetForm.username = this.$route.params.username
    }
  }
</script>

<style>
  @import "../common/css/login.css";
</style>
