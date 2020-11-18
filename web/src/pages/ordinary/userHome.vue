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
        <el-menu-item index="1">
          <i class="el-icon-star-off"></i>
          <span slot="title">
              <router-link to="/user/index">网站首页</router-link>
            </span>
        </el-menu-item>
        <el-submenu index="2">
          <template slot="title">
            <i class="el-icon-user"></i>
            {{username}}
          </template>
          <el-menu-item index="2-1">
            <i class="el-icon-help"></i>
            <router-link to="/user/my">个人信息</router-link>
          </el-menu-item>
          <el-menu-item index="2-2">
            <i class="el-icon-coin"></i>
            <span @click="logout">退出登录</span>
          </el-menu-item>
          <el-menu-item index="2-3">
            选项3
          </el-menu-item>
        </el-submenu>
      </el-menu>
    </el-header>
    <el-container>
      <el-aside width="200px" class="aside">
        <el-menu
          background-color="#545c64"
          text-color="#fff"
          active-text-color="#ffd04b"
          :unique-opened="true"
          style="font-size: 30px!important;font-weight: bold">
          <el-menu-item index="1">
            <i class="el-icon-star-off"></i>
            <span slot="title">
              <router-link to="/user/index">网站首页</router-link>
            </span>
          </el-menu-item>
          <el-submenu index="2">
            <template slot="title">
              <i class="el-icon-suitcase"></i>
              <span>申请管理</span>
            </template>
            <el-menu-item index="2-1">
              <i class="el-icon-reading"></i>
              模板申请</el-menu-item>
            <el-menu-item index="2-2" >
              <i class="el-icon-folder-add"></i>
              <router-link to="/user/application/create">新建申请</router-link>
            </el-menu-item>
            <el-menu-item index="2-3">
              <i class="el-icon-view"></i>
              <router-link to="/user/application/look">查看申请</router-link>
            </el-menu-item>
          </el-submenu>
          <el-submenu index="3">
            <template slot="title">
              <i class="el-icon-position"></i>
              <span>审批管理</span>
            </template>
            <el-menu-item index="3-1">
              <i class="el-icon-document"></i>
              <router-link to="/user/manage/pendmanage">待审批</router-link>
            </el-menu-item>
            <el-menu-item index="3-2">
              <i class="el-icon-finished"></i>
              <router-link to="/user/manage/finishmanage">已审批</router-link>
            </el-menu-item>
          </el-submenu>

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
    name: "userHome",
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
