<template>
    <!--审批查看-->
  <div>
    <div>
      <el-breadcrumb separator="/">
        <el-breadcrumb-item>审批管理</el-breadcrumb-item>
        <el-breadcrumb-item>待审批</el-breadcrumb-item>
      </el-breadcrumb>
      <el-divider></el-divider>
    </div>
    <div>
      <div>
        <span class="title">基本信息</span><br/>
        <table>
          <th>申请人</th>
          <th>申请表名称</th>
          <th>审批人列表</th>
          <th>审批阶段</th>
          <th>当前审批人</th>
          <th>审批意见</th>
          <tr>
            <td>{{Info.userid}}</td>
            <td>{{Info.name}}</td>
            <td><span v-for="x in Info.member">{{x}}**</span></td>
            <td>{{Info.stage}}</td>
            <td>{{Info.auth}}</td>
            <td>{{Info.result}}</td>
          </tr>
        </table>
      </div>
      <el-divider></el-divider>
      <div v-if="manageInfo.length>0">
        <span class="title">审批人意见一览</span><br>
        <span v-for="(x ,index) in manageInfo">
          <span>审批人：{{x.userid}}</span>
          <span>审批意见：{{x.flow}}</span>
          <span>审批备注：{{x.other}}</span>
          <span>审批时间：{{x.ftime}}</span>
        </span>
      </div>
      <div v-else>
        暂无审批人审核！
      </div>
      <el-divider></el-divider>
      <div>
        <span class="title">审批内容</span>
        <div v-for="(x,index) in appInfo">
          <span>{{x.fname}}:{{x.content}}</span>
        </div>
      </div>
    </div>
    <el-divider></el-divider>
    <div>
      <el-form>
          <el-form-item>
            <el-select v-model="OpiniodList.flow">
              <el-option value="1">通过</el-option>
              <el-option value="2">不通过</el-option>
            </el-select>
          </el-form-item>
          <el-form-item>
            <el-input v-model="OpiniodList.other" placeholder="注备信息" style="width: 300px"></el-input>
          </el-form-item>
      </el-form>
      <el-button>提交审批</el-button>
    </div>
  </div>
</template>

<script>
    export default {
        name: "LookManage",
      data(){
          return{
            Info:[],
            manageInfo:[],
            appInfo:[],
            mid:'',
            uid:this.$store.getters.getsId,
            OpiniodList:{
              flow:'',
              other:''
            }
          }
      },
      created()
      {
        this.mid = this.$route.query.id
        this.getAppList()
      },
      methods:{
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
        submitManage:function () {

          let that =this
          if(that.OpiniodList.flow=='')
          {
            this.$alert('还未填写审核意见', '提示', {
              confirmButtonText: '确定',
              type:'warning',
              center: true
            })
          }
          else
          {
            that.$http.post('/flow/flow',{
              uid:that.uid,
              mid:that.mid,
              flow:that.OpiniodList.flow,
              other:that.OpiniodList.other
            }).then(function (res) {


            })
          }

        }
      }
    }
</script>

<style scoped>
@import "../../../common/css/common.css";
</style>
