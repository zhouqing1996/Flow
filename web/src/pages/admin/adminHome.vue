<template>
  <el-container>
    <el-header class="header">
      <el-menu
        background-color="#0ea0db"
        text-color="#fff"
        active-text-color="#ffd04b"
        mode="horizontal"
        :unique-opened="true"
        style="font-size: 30px!important;font-weight: bold;float: right">
        <el-menu-item index="1" style="width: 150px">
          <!--<i class="el-icon-star-off"></i>-->
              <router-link to="/admin/index" class="el-icon-star-off">网站首页</router-link>
        </el-menu-item>
        <el-submenu index="2">
          <template slot="title">
            <i class="el-icon-user"></i>
            {{username}}
          </template>
          <el-menu-item index="2-1">
              <!--<i class="el-icon-help"></i>-->
              <router-link to="/admin/my" class="el-icon-help" >个人信息</router-link>
          </el-menu-item>
          <el-menu-item index="2-2">
            <span @click="logout" class="el-icon-coin">退出登录</span>
          </el-menu-item>
        </el-submenu>
      </el-menu>
    </el-header>
    <el-container>
      <el-aside width="200px" class="aside">
        <el-menu
          background-color="#FF7F24"
          text-color="#fff"
          active-text-color="#3A5FCD"
          :unique-opened="true"
          style="font-size: 30px!important;font-weight: bold">
          <el-menu-item index="1">
              <!--<i  class="el-icon-star-off"></i>-->
              <router-link to="/admin/index" class="el-icon-star-off">网站首页</router-link>
          </el-menu-item>
          <el-menu-item index="2">
              <!--<i class="el-icon-user"></i>-->
              <router-link to="/admin/user" class="el-icon-user">用户管理</router-link>
          </el-menu-item>
          <el-menu-item index="3">
            <!--<i class="el-icon-position"></i>-->
                <router-link to="/admin/application/look" class="el-icon-position">申请管理</router-link>
          </el-menu-item>
        </el-menu>
      </el-aside>
      <el-container>
        <el-main class="main">
          <router-view/>
        </el-main>
        <el-footer class="footer">Footer</el-footer>
      </el-container>
    </el-container>
  </el-container>
</template>

<script>
    export default {
        name: "adminHome",
      data()
      {
        return{
          username:this.$store.getters.getsName
        }
      },
      methods:{
          logout:function () {
            this.$confirm('退出登录, 是否继续?', '提示', {
              confirmButtonText: '确定',
              cancelButtonText: '取消',
              type: 'warning',
              center: true
            }).then(() => {
              this.$store.dispatch('slogout')
              this.$router.push({
                path:'/login'
              })
              this.$message({
                type: 'success',
                message: '退出成功!'
              });
            }).catch(() => {
              this.$message({
                type: 'info',
                message: '已取消删除'
              });
            });
          }
      }
    }
</script>

<style scoped>
@import "../../common/css/home.css";
</style>
