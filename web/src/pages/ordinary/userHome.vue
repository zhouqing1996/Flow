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
            <router-link to="/user/index" class="el-icon-star-off">网站首页</router-link>
        </el-menu-item>
        <el-submenu index="2">
          <template slot="title">
            <span class="el-icon-user"></span>
            {{username}}
          </template>
          <el-menu-item index="2-1">
            <!--<i class="el-icon-help"></i>-->
              <!--<span class="el-icon-help"></span>-->
              <router-link to="/user/my" class="el-icon-help">个人信息</router-link>
          </el-menu-item>
          <el-menu-item index="2-2">
            <!--<i class="el-icon-coin"></i>-->
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
            <!--<i class="el-icon-star-off"></i>-->
              <router-link to="/user/index" class="el-icon-star-off">网站首页</router-link>
          </el-menu-item>
          <el-submenu index="2">
            <template slot="title">
              <span class="el-icon-suitcase">申请管理</span>
            </template>
            <el-menu-item index="2-1" >
                <!--<i class="el-icon-folder-add"></i>-->
              <router-link to="/user/application/create" class="el-icon-folder-add">新建申请</router-link>
            </el-menu-item>
            <el-menu-item index="2-2">
                <!--<i class="el-icon-view"></i>-->
                <router-link to="/user/application/look" class="el-icon-view">查看申请</router-link>
            </el-menu-item>
          </el-submenu>
          <el-submenu index="3">
            <template slot="title">
              <span class="el-icon-position">审批管理</span>
            </template>
            <el-menu-item index="3-1">
                <router-link to="/user/manage/pendmanage" class="el-icon-folder">待审批</router-link>
            </el-menu-item>
            <el-menu-item index="3-2">
                <!--<i class="el-icon-finished"></i>-->
                <router-link to="/user/manage/finishmanage" class="el-icon-finished">已审批</router-link>
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
