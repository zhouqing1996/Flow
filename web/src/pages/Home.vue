<template>
  <div>
    <el-container>
      <el-header class="header">
        <el-menu
          class="el-menu-demo"
          mode="horizontal"
          background-color="#3686f1"
          text-color="#FFFFFF"
          active-text-color="#ffd04b"
          float="right">
          <el-menu-item disabled>       </el-menu-item>
          <el-menu-item disabled>        </el-menu-item>
          <el-menu-item disabled>       </el-menu-item>
          <el-menu-item disabled>        </el-menu-item>
          <el-menu-item disabled>       </el-menu-item>
          <el-menu-item disabled>        </el-menu-item>
          <el-menu-item disabled>       </el-menu-item>
          <el-menu-item disabled>        </el-menu-item>
          <el-menu-item disabled>       </el-menu-item>
          <el-menu-item disabled>        </el-menu-item>
          <el-menu-item disabled>       </el-menu-item>
          <el-menu-item disabled>        </el-menu-item>
          <el-menu-item disabled>       </el-menu-item>
          <el-menu-item disabled>        </el-menu-item>
          <el-menu-item disabled>       </el-menu-item>
          <el-menu-item disabled>        </el-menu-item>
          <el-menu-item disabled>        </el-menu-item>
          <el-menu-item disabled>       </el-menu-item>
          <el-menu-item disabled>        </el-menu-item>
          <el-menu-item disabled>       </el-menu-item>
          <el-menu-item disabled>        </el-menu-item>
          <el-menu-item disabled>        </el-menu-item>
          <el-menu-item disabled>       </el-menu-item>
          <el-menu-item index="1" style="font-size: 16px">
            <router-link to="/question">
              首页
            </router-link>
          </el-menu-item>
          <el-menu-item index="2" style="font-size: 16px">
            <router-link to="/question/analyse">
              数据分析
            </router-link>
          </el-menu-item>
          <el-menu-item index="4" style="font-size: 16px">
            <router-link to="/question/user">
              用户管理
            </router-link>
          </el-menu-item>
          <el-submenu index="3" >
            <template slot="title">
              <el-avatar src="https://cube.elemecdn.com/0/88/03b0d39583f48206768a7534e55bcpng.png">注销</el-avatar>
            </template>
            <el-menu-item index="3-1"><router-link to="/my">我的资料</router-link></el-menu-item>
            <!--<el-menu-item index="3-2"><router-link to="/changepwd">修改密码</router-link></el-menu-item>-->
            <el-menu-item index="3-3" v-on:click="logout" >注销
            </el-menu-item>
          </el-submenu>

        </el-menu>
      </el-header>
      <el-main class="main" v-bind:style="{minHeight: Height+'px'}">
        <router-view/>
      </el-main>
      <el-footer class="footer">
        <p>Copyrights©{{CurrentYear}} by zhouqing.</p>
      </el-footer>
    </el-container>
  </div>
</template>

<script>
  export default {
    name: "Home",
    data(){
      var copyright=new Date();
      var update=copyright.getFullYear()
      return {
        CurrentYear:update,
        Height:0
      }
    },
    methods: {
      logout:function () {
        let suserid = this.$store.getters.getsId
        console.log(suserid)
        let that = this
        this.$confirm("是否退出？", "提示", {
          confirmButtonText: "确定",
          cancelButtonText: "取消",
          type: "warning"
        }).then(function (res) {
          that.$store.dispatch('slogout')
          alert("退出成功！")
          that.$router.push({path: '/login'})
        }).catch(function (err) {
          console.log(err)
        })
      }
    },
    created(){

    },
    mounted(){
      //动态设置内容高度 让footer始终居底   header+footer的高度是100
      this.Height = document.documentElement.clientHeight - 50;
      //监听浏览器窗口变化　
      window.onresize = ()=> {this.Height = document.documentElement.clientHeight -50}
    }
  }
</script>

<style scoped>
  p {
    font-size: 14px;
    margin: auto;
    color: #3d3d3d;
  }
  .header{
    position: fixed;
    top:0;
    left:0;
    z-index: 50;
    /*background-image: none;*/
    background-color: #3686f1;
    font-size: 20px;
    color: white;
    width: 100%;
    border-color: #1995dc;
    text-align: right;
  }
  .main{
    height: 80%;
    width: 100%;
    margin-top: 30px;
    padding: 10px;
    margin-left: 30px;

  }
  .footer{
    position: relative;
    bottom: 0;
    width: 100%;
    text-align: center;
    height: 30px;/*脚部的高度*/
    clear:both;
  }
  a {
    text-decoration: none;
    color: white;
  }
  .router-link-active {
    text-decoration: none;
  }
</style>
