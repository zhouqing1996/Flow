<template>
  <div class="page">
    <div class="loginwarrp">
      <div class="logo">文件流转系统</div>
      <div class="login_form">
        <li class="login-item" >
          <span style="font-size: 20px;color: #0ea0db;" class="el-icon-user">用户工号：</span>
          <input type="text" v-model="loginForm.username" class="login_input" placeholder="账号">
          <span class="error"></span>
        </li>
        <li class="login-item">
          <span style="font-size: 20px;color: #0ea0db;" class="el-icon-key">登录密码：</span>
          <input :type="passwordVisible" v-model="loginForm.password" class="login_input" placeholder="密码">
          <i slot="suffix" :class="icon" @click="showPass" style="text-align: right"></i>
          <span class="error"></span>
        </li>
        <div class="clearfix">
          <li class="login-sub">
            <input type="button" value="登录" v-on:click="login">
          </li>
          <br>
        </div>
        <div class="clearfix">
          <li class="login-sub">
            <router-link to="/register">立即注册</router-link>
            <router-link to="/forget">找回密码</router-link>
          </li>
        </div>
      </div>
    </div>

  </div>
</template>


<script>
  export default {
    name: "Login",
    data() {
      return {
        passwordVisible:'password',
        icon:"el-icon-view",
        loginForm: {
          username: '',
          password: '',
        },
        responseResult: []
      }
    },
    methods: {
      showPass() {
        if (this.passwordVisible === "text") {
          this.passwordVisible = "password";
          //更换图标
          this.icon = "el-icon-view";
        } else {
          this.passwordVisible = "text";
          this.icon = "el-icon-lock";
        }
      },
      login() {
        if(this.loginForm.username==''||this.loginForm.password=='')
        {
          this.$alert('请输入完整登录信息', '警告', {
            confirmButtonText: '确定',})
        }
        else{
          this.$http.post('/home/index/login',{
            username:this.loginForm.username,
            password:this.loginForm.password,
          }).then(res=>{
            console.log(res.data)
            var message = res.data.message
            if(message=="登录成功")
            {
              this.$store.dispatch('slogin',res.data.data)
              let role = this.$store.getters.getsRole
              alert(message)
              if(role==1)
              {
                //管理员
                this.$router.push({path:'/admin/index'})
              }
              else if(role==2)
              {
                //教师
                this.$router.push({path:'/user/index'})
              }
              console.log(this.$store.getters.getsToken)
            }
            else if(message=="该用户不存在")
            {
              alert(message)
            }
            else if(message=="密码错误，登录失败")
            {
              alert(message)
            }
          }).catch(function (error) {
            console.log(error)
          })
        }
      },
    },
    created:function ()
    {
      this.loginForm.username = this.$route.params.username;
      this.loginForm.password = this.$route.params.password
    }
  }
</script>

<style scoped>
  @import "../common/css/login.css";
</style>
