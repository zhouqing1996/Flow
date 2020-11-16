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
        <li class="login-item">
          <span style="font-size: 20px;color: #0ea0db;" class="el-icon-key"> 新 密 码：</span>
          <input :type="passwordVisible1" v-model="forgetForm.password" class="login_input" placeholder="6-10位包含数字、字母、特殊字符">
          <i slot="suffix" :class="icon1" @click="showPass1" style="text-align: right"></i>
          <span class="error"></span>
        </li>
        <li class="login-item">
          <span style="font-size: 20px;color: #0ea0db;" class="el-icon-key">确认密码：</span>
          <input :type="passwordVisible2" v-model="forgetForm.password2" class="login_input" placeholder="6-10位包含数字、字母、特殊字符">
          <i slot="suffix" :class="icon2" @click="showPass2" style="text-align: right"></i>
          <span class="error"></span>
        </li>
        <div class="clearfix">
          <li class="login-sub">
            <input type="button" value="确认" v-on:click="forget">
          </li>
          <br>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
  export default {
    name: "newpass",
    data() {
      return {
        passwordVisible1:'password',
        icon1:"el-icon-view",
        passwordVisible2:'password',
        icon2:"el-icon-view",
        forgetForm: {
          password: '',
          password2:'',
          username: "",
        },
        responseResult: []
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
      forget() {
        let that =this
        var Reg = new RegExp(/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[~!@#$%^&*()_+`\-={}:";'<>,.\/]).{6,10}/)
        var EmailReg = new RegExp(/[0-9a-zA-Z_.-]+[@][0-9a-zA-Z_.-]+([.][a-zA-Z]+){1,2}/)
        if(!Reg.test(that.forgetForm.password))
        {
          that.$alert('密码规则为：6-10位包含数字、字母、特殊字符的字串', '警告', {
            confirmButtonText: '确定',})
        }
        else if(that.forgetForm.password!=that.forgetForm.password2)
        {
          that.$alert('两次密码输入不正确！', '警告', {
            confirmButtonText: '确定',})
        }
        else if(that.forgetForm.username=="")
        {
          that.$alert('用户名为空！', '警告', {
            confirmButtonText: '确定',})
        }
        else {
          that.$http.post('/home/index/newpass',{
            password:that.forgetForm.password,
            username:that.forgetForm.username
          }).then(res=>{
            console.log(res.data)
            if(res.data.message=="修改密码成功")
            {
              alert('修改密码成功')
              that.$store.dispatch('slogout')
              that.$router.push({
                path:'/login',
                params:{
                  username:this.forgetForm.username
                }
              })
            }
            else {
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

<style>
  @import "../common/css/login.css";
</style>
