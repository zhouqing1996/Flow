<template>
    <!--查看申请表的详细信息-->
  <div>
    <div>
      <el-breadcrumb separator="/">
        <el-breadcrumb-item>申请管理</el-breadcrumb-item>
        <el-breadcrumb-item>查看申请(申请内容查看)</el-breadcrumb-item>
      </el-breadcrumb>
      <el-divider></el-divider>
    </div>
    <div>
      <el-page-header @back="goBack" content="申请列表">
      </el-page-header>
    </div>
    <div>
      <div>
        <span class="title">基本信息</span><br/>
        <table>
          <th>申请人</th>
          <th>申请表名称</th>
          <th>申请时间</th>
          <tr>
            <td>{{Info.userid}}</td>
            <td>{{Info.name}}</td>
            <td>{{Info.ctime}}</td>
          </tr>
        </table>
        <table>
          <th style="width: 80%">审批人列表</th>
          <tr>
            <td>
              <span v-for="x in Info.member">{{x}}**</span>
            </td>
          </tr>
        </table>
        <table>
          <th>审批阶段</th>
          <th>当前审批人</th>
          <th>审批意见</th>
          <tr>
            <td>{{Info.stage}}</td>
            <td>{{Info.auth}}</td>
            <td>{{Info.result}}</td>
          </tr>
        </table>
      </div>
      <el-divider></el-divider>
      <div v-if="manageInfo.length>0">
        <span class="title">审批人意见一览</span><br>
        <table>
          <th>审批人</th>
          <th>审批意见</th>
          <th>审批备注</th>
          <th>审批时间</th>
          <tr v-for="(x ,index) in manageInfo">
            <td>{{x.userid}}</td>
            <td>{{x.flow}}</td>
            <td>{{x.other}}</td>
            <td>{{x.ftime}}</td>
          </tr>
        </table>
      </div>
      <el-divider></el-divider>
      <div>
        <span class="title">审批内容</span>
        <table>
          <th>项目</th>
          <th>内容</th>
          <tr v-for="(x,index) in appInfo">
            <td>{{x.fname}}</td>
            <td>{{x.content}}</td>
          </tr>
        </table>
      </div>
    </div>
    </div>
</template>

<script>
    export default {
        name: "LookApp2",
      data(){
          return{
            Info:[],
            manageInfo:[],
            appInfo:[],
            mid:'',
            uid:this.$store.getters.getsId,
          }
      },
      created(){
        this.mid = this.$route.query.id
        this.getAppList()
      },
      methods:{
        goBack:function() {
            this.$router.push({
              path: '/user/application/look',
            })
        },
        getAppList:function () {
          //  获得申请表内容
          let that =this
          that.$http.post('/flow/flow/lookmanage',{
            id:that.mid
          }).then(function (res) {
            console.log(res.data)
            that.Info = res.data.data[0]
            that.manageInfo = res.data.data[1]
            that.appInfo =res.data.data[2]
          })
        },

      }
    }
</script>

<style scoped>
  @import "../../../common/css/common.css";
</style>
