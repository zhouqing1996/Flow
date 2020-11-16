<template>
    <!--修改密码-->
  <div>
    <div class="back">
      <el-page-header @back="back">
      </el-page-header>
    </div>
    <el-form class="change-container" label-position="left" label-width="0px">
      <h3 class="change_title">修改密码</h3>
      <el-form-item>
        <!--<el-input type="password" v-model="newpwd" auto-complete="off" placeholder="密码"></el-input>-->
        <el-input :type="passwordVisible" v-model="newpwd" auto-complete="off" placeholder="密码">
          <i slot="suffix" :class="icon" @click="showPass"></i>
        </el-input>
      </el-form-item>

      <el-form-item>
        <el-input :type="passwordVisible1" v-model="newpwds" auto-complete="off" placeholder="确认密码">
          <i slot="suffix" :class="icon1" @click="showPass1"></i>
        </el-input>
      </el-form-item>

      <el-form-item style="width: 100%">
        <el-button type="primary" style="width: 100%;background: dodgerblue;border: none" v-on:click="changePwd(newpwd,newpwds)">确定</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
    export default {
        name: "Changepwd",
      data(){
          return{
            passwordVisible:'password',
            icon:"el-icon-view",
            passwordVisible1:'password',
            icon1:"el-icon-view",
            pwd:this.$store.getters.getsPwd,
            newpwd:'',
            newpwds:''
          }
      },
      methods:{
        back:function()
        {
          let that = this
          if(that.userrole==1)
          {
            this.$router.push({
              path:'/question',
            })
          }
          else {
            this.$router.push({
              path:'/u/question',
            })
          }

        },
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
          //修改密码
          changePwd:function (newpwd,newpwds) {
            if(newpwd==newpwds) {
              this.$http.post('/home/user/changeuser', {
                flag: 3,
                userid: this.$store.getters.getsId,
                password: newpwd
              }).then(res => {
                console.log(res.data)
                if (res.data.message == "和原始密码一致") {
                  alert(res.data.message)
                }
                else if (res.data.message == "该用户密码修改成功") {
                  console.log(res.data)
                  this.$store.dispatch('logout')
                  this.$store.dispatch('slogout')
                  // this.$store.dispatch('userLogin',true)
                  alert("密码修改成功！")
                  this.$router.push({path: '/login'})
                }
                else {
                  console.log(res.data.message)
                  alert(res.data.message)
                }
              }).catch(() => {
              })
            }
            else{
              alert("密码输入不一致！")
            }
          }
      }
    }
</script>

<style scoped>
  .change-container {
    border-radius: 15px;
    background-clip: padding-box;
    margin: 90px auto;
    width: 350px;
    padding: 35px 35px 15px 35px;
    background: #fff;
    border: 1px solid #eaeaea;
    box-shadow: 0 0 25px #cac6c6;
  }
  .change_title {
    margin: 0px auto 40px auto;
    text-align: center;
    color: #505458;
  }
  .back{
    margin-top: 30px;
    margin-bottom: 20px;
  }
</style>
