<template>
  <!--我的资料-->
  <div>
    <div>
      <el-breadcrumb separator="/">
        <el-breadcrumb-item>个人信息</el-breadcrumb-item>
        <el-breadcrumb-item>我的资料</el-breadcrumb-item>
      </el-breadcrumb>
      <el-divider></el-divider>
    </div>
    <div class="info">
      <p>用户工号：{{userno}}</p>
      <p>用 户 名：{{username}}</p>
    </div>
  </div>
</template>

<script>
  export default {
    name: "my",
    data(){
      return {
        userid:this.$store.getters.getsId,
        username:this.$store.getters.getsName,
        userno:this.$store.getters.getsNo,
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
            path:'/admin/index',
          })
        }
        else {
          this.$router.push({
            path:'/user/index',
          })
        }
      },
    }
  }
</script>

<style scoped>
 @import '../common/css/my.css'
</style>
