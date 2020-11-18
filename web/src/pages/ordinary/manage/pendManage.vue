<template>
    <div>
<!--      待审批-->
      <div>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item>审批管理</el-breadcrumb-item>
          <el-breadcrumb-item>待审批</el-breadcrumb-item>
        </el-breadcrumb>
        <el-divider></el-divider>
      </div>
      <div>
        <table>
          <th>审批编号</th>
          <th>审批名称</th>
          <th>申请人</th>
          <th>申请时间</th>
          <th>审批阶段</th>
          <th>当前审批人</th>
          <th>审批意见</th>
          <th>操作</th>
          <tr v-for="(x,index) in pendList">
            <td>{{x.id}}</td>
            <td>{{x.name}}</td>
            <td>{{x.userid}}</td>
            <td>{{x.ctime}}</td>
            <td>{{x.stage}}</td>
            <td>{{x.auth}}</td>
            <td>{{x.result}}</td>
            <td>
              <span class="look" @click="lookManage(x.id)">查看</span>
              <span >审批</span>
            </td>
          </tr>
        </table>
      </div>
    </div>
</template>

<script>
    export default {
        name: "pendManage",
      data() {
        return{
          pendList:[],
          uid:this.$store.getters.getsId
        }
      },
      created(){
          this.getPendList()
      },
      methods:{
          lookManage:function(id)
          {
            this.$router.push({
              path:'/user/manage/lookmanage',
              query:{
                id:id
              }
            })

          },
          getPendList:function () {
            let that =this
            that.$http.post('/flow/flow/pendmanage',{
              id:that.uid
            }).then(function (res) {
              console.log(res.data)
              if(res.data.message=="还没有待审批的文件")
              {
                that.pendList = []
              }
              else
              {
                that.pendList = []
                let t = res.data.data
                for(let i=0;i<t.length;i++)
                {
                  that.pendList.push({
                    id:t[i].id,
                    name:t[i].name,
                    userid:t[i].userid,
                    stage:t[i].stage,
                    auth:t[i].auth,
                    result:t[i].result,
                    ctime:t[i].ctime,
                    status:t[i].status
                  })
                }
              }
            })
          }
      }
    }
</script>

<style scoped>
@import "../../../common/css/common.css";
</style>
