<template>
  <div class="page">
    <div class="loginwarrp">
      <div class="logo">文件流转系统（注册）</div>
      <div class="login_form">
        <li class="login-item" >
          <span style="font-size: 20px;color: #0ea0db;" class="el-icon-user">用户工号：</span>
          <input type="text" v-model="registerForm.no" class="login_input" placeholder="用户名">
          <span class="error"></span>
        </li>
        <li class="login-item" >
          <span style="font-size: 20px;color: #0ea0db;" class="el-icon-star-off"> 用 户 名：</span>
          <input type="text" v-model="registerForm.username" class="login_input" placeholder="用户名">
          <span class="error"></span>
        </li>
        <li class="login-item">
          <span style="font-size: 20px;color: #0ea0db;" class="el-icon-key">设置密码：</span>
          <input :type="passwordVisible1" v-model="registerForm.password" class="login_input" placeholder="6-10位包含数字、字母、特殊字符">
          <i slot="suffix" :class="icon1" @click="showPass1"></i>
          <span class="error"></span>
        </li>
        <li class="login-item">
          <span style="font-size: 20px;color: #0ea0db;" class="el-icon-key">确认密码：</span>
          <input :type="passwordVisible2" v-model="registerForm.password1" class="login_input" placeholder="密码">
          <i slot="suffix" :class="icon2" @click="showPass2"></i>
          <span class="error"></span>
        </li>
        <div class="clearfix">
          <li class="login-sub">
            <input type="button" value="注册" v-on:click="submitForm(registerForm)">
          </li>
          <span class="error"></span>
        </div>
        <div  >
          <span><router-link to="/login">立即登录</router-link></span>
          <span><router-link to="/forget">找回密码</router-link></span>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    name: "register",
    data() {
      return {
        registerForm: {
          userno:'',
          password: "",
          password1: "",
          username: "",
          role:'',
          no:''
        },
        flag: true,
        passwordVisible1:'password',
        passwordVisible2:'password',
        icon1:"el-icon-view",
        icon2:"el-icon-view",
      }
    },
    methods: {
      showPass1() {
        if (this.passwordVisible1 === "text") {
          this.passwordVisible1 = "password";
          //更换图标
          this.icon1 = "el-icon-view";
        } else {
          this.passwordVisible1 = "text";
          this.icon1 = "el-icon-lock";
        }
      },
      showPass2() {
        if (this.passwordVisible2 === "text") {
          this.passwordVisible2 = "password";
          //更换图标
          this.icon2 = "el-icon-view";
        } else {
          this.passwordVisible2 = "text";
          this.icon2 = "el-icon-lock";
        }
      },
      submitForm(formName) {
        var Reg = new RegExp(/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[~!@#$%^&*()_+`\-={}:";'<>,.\/]).{6,10}/)
        var No = new RegExp(/^[0-9]{6,20}/)
        if(!No.test(this.registerForm.no))
        {
          this.$alert('工号不正确，应为6-20位的数字组成！', '警告', {
            confirmButtonText: '确定',})
        }
        else if(!Reg.test(this.registerForm.password))
        {
          this.$alert('密码规则为：6-10位包含数字、字母、特殊字符的字串', '警告', {
            confirmButtonText: '确定',})
        }
        else if( this.registerForm.username=='')
        {
          this.$alert('用户名不能为空！', '警告', {
            confirmButtonText: '确定',})
        }
        else if(this.registerForm.password1 != this.registerForm.password)
        {
          this.$alert('两次输入密码不一致', '警告', {
            confirmButtonText: '确定',})
        }
        else{
          console.log(this.registerForm.username);
          console.log(this.registerForm.password);
          this.$http.post('/home/index/register',{
            username:this.registerForm.username,
            password:this.registerForm.password,
            no:this.registerForm.no
          }).then(res=>{
            console.log(res.data)
            if(res.data.message=="注册成功")
            {
              alert('注册成功')
              this.$router.push({
                path:'/login',
                params:{
                  username:this.registerForm.username,
                  password:this.registerForm.password
                }
              })
            }
            else
            {
              alert(res.data.message)
            }
          }).catch(function (error) {
            console.log(error)
          })
        }
      },
    }
  }
</script>

<style scoped>
  @import "../common/css/login.css";
</style>
