<template>
    <!--审批查看-->
  <div>
    <div>
      <el-breadcrumb separator="/">
        <el-breadcrumb-item>审批管理</el-breadcrumb-item>
        <el-breadcrumb-item>待审批(审批内容查看)</el-breadcrumb-item>
      </el-breadcrumb>
      <el-divider></el-divider>
    </div>
    <div>
      <el-page-header @back="goBack" :content="c">
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
      <div v-else>
        暂无审批人审核！
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
    <div v-if="flag==2">
      <el-divider></el-divider>
      <span class="title">审批意见</span>
      <el-form>
          <el-form-item>
            <el-select v-model="OpiniodList.flow">
              <el-option value="1" label="通过">通过</el-option>
              <el-option value="2" label="不通过">不通过</el-option>
            </el-select>
          </el-form-item>
          <el-form-item>
            <el-input v-model="OpiniodList.other" placeholder="注备信息" style="width: 300px"></el-input>
          </el-form-item>
      </el-form>
      <el-button @click="submitManage">提交审批</el-button>
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
            },
            flag:0,
            c:''
          }
      },
      created()
      {
        this.mid = this.$route.query.id
        this.flag =this.$route.query.flag
        if(this.flag==1)
        {
          this.c='已完成审批列表'
        }
        else{
          this.c='待审批列表'
        }
        this.getAppList()
      },
      methods:{
        goBack:function()
        {
          if(this.flag==1)
          {
            this.$router.push({
              path:'/user/manage/finishmanage',
            })
          }
          else{
            this.$router.push({
              path:'/user/manage/pendmanage',
            })
          }
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
            that.$http.post('/flow/flow/manage',{
              uid:that.uid,
              mid:that.mid,
              flow:that.OpiniodList.flow,
              other:that.OpiniodList.other
            }).then(function (res) {
              console.log(res.data)
              if(res.data.message=="审批成功")
              {
                that.$router.push({
                  path:'/user/manage/finishmanage'
                })
              }
            })
          }

        }
      }
    }
</script>

<style scoped>
@import "../../../common/css/common.css";
</style>
