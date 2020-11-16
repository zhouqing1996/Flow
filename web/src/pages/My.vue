<template>
  <!--我的资料-->
  <div>

    <el-aside :span="4"></el-aside>
    <el-main :span="16">
      <div class="waimian">
        <div class="back">
          <el-page-header @back="back">
          </el-page-header>
        </div>
        <div class="info">
          <el-form class="my-container" label-position="left" label-width="0px">
            <h3 class="my_title">我的资料</h3>
            <p>用户编号：{{this.userid}}</p>
            <p> 用  户 名：{{this.username}}</p>
            <p v-if="this.userrole==1">身   份：网站管理员</p>
            <p v-if="this.userrole==2">身   份：教师</p>
            <p v-if="this.userrole==3">身   份：学生</p>
          </el-form>
        </div>
      </div>
    </el-main>
    <el-aside :span="4"></el-aside>

  </div>
</template>

<script>
  export default {
    name: "my",
    data(){
      return {
        userid:this.$store.getters.getsId,
        username:this.$store.getters.getsName,
        userrole:this.$store.getters.getsRole,
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
    }
  }
</script>

<style scoped>
  .info{
    text-align: left;

  }
  .waimian{
    margin-top: 10px;
    padding: 10px;
    background-color: aliceblue;
    margin-left: 20px;
    margin-right: 20px;
    border: white;
    width: auto;
    height: auto;
  }
  .back{
    margin-top: 40px;
    margin-bottom: 20px;
  }
  .my-container {
    border-radius: 15px;
    background-clip: padding-box;
    margin: 90px auto;
    width: 300px;
    padding: 35px 35px 15px 35px;
    background: #fff;
    border: 1px solid #eaeaea;
    box-shadow: 0 0 25px #cac6c6;
  }
  .my_title {
    margin: 0px auto 40px auto;
    text-align: center;
    color: #505458;
  }
</style>
