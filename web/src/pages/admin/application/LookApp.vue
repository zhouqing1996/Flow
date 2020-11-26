<template>
<!--    查看申请-->
  <div>
    <div>
      <el-breadcrumb separator="/">
        <el-breadcrumb-item>申请管理</el-breadcrumb-item>
        <el-breadcrumb-item>查看申请</el-breadcrumb-item>
      </el-breadcrumb>
      <el-divider></el-divider>
    </div>
    <!--<div v-if="appList.length==0">-->
      <!--{{username}}还未有任何申请！-->
    <!--</div>-->
    <div >
      <table>
        <th>申请编号</th>
        <th>申请人</th>
        <th>申请名称</th>
        <th>审批人列表</th>
        <th>申请状态</th>
        <th>当前审批人</th>
        <th>审批结果</th>
        <th>申请时间</th>
        <th>操作</th>
        <tr v-for="(x,i) in currentPageData">
          <td>{{x.id}}</td>
          <td>{{x.userid}}</td>
          <td>{{x.name}}</td>
          <td>
            <span v-for="xx in x.member">{{xx}}</span>
          </td>
          <td>{{x.stage}}</td>
          <td>{{x.auth}}</td>
          <td>{{x.result}}</td>
          <td>{{x.ctime}}</td>
          <td>
            <span class="download" @click="downloadFile(x.id)">下载</span>
            <span @click="LookApp(x.id)" class="look">查看</span>
            <span @click="deleteApp(x.id)" class="delete">删除</span>
          </td>
        </tr>
      </table>
    </div>
    <div class="page">
      <ul class="pagination pagination-sm"><!--分页-->
        <li class="page-item" v-if="currentPage!=1">
          <span class="page-link" v-on:click="prePage">上一页</span>
        </li>
        <li class="page-item" >
          <span class="page-link" >第{{ currentPage }}页/共{{totalPage}}页</span>
        </li>
        <li class="page-item" v-if="currentPage!=totalPage">
          <span class="page-link" v-on:click="nextPage">下一页</span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
    export default {
        name: "LookApp",
      data(){
          return{
            appList:[],
            username:this.$store.getters.getsName,
            // 翻页相关
            currentPage: 1,
            totalPage: 1,
            pageSize: 10,
            currentPageData:[]
          }
      },
      created() {
          this.getAppList()
      },
      methods:{
        downloadFile:function(id)
        {
          let that = this
          that.$http.post('/flow/download/download',{
            id:id
          }).then(function (res) {
            console.log(res.data)
            window.open(res.data.data[0])
          })
        },
          LookApp:function(id)
          {
            this.$router.push({
              path:'/admin/application/lookapp',
              query:{
                id:id,
              }
            })
          },
          deleteApp:function(id)
          {
            let that = this
            that.$http.post('/flow/flow/deleteflow',{
              id:id
            }).then(function (res) {
              console.log(res.data)
              alert(res.data.message)
              that.getAppList()
            })

          },
        //分页
        setCurrentPageDate: function () {
          let begin = (this.currentPage - 1) * this.pageSize;
          let end = this.currentPage * this.pageSize;
          this.currentPageData = this.appList.slice(begin, end)
        },
        prePage() {
          console.log(this.currentPage)
          if (this.currentPage == 1)
            return
          this.currentPage--;
          this.setCurrentPageDate()
        },
        nextPage() {
          if (this.currentPage == this.totalPage) return
          this.currentPage++;
          this.setCurrentPageDate()
        },
          getAppList:function () {
            let that = this
            that.$http.post('/flow/flow/queryapp',{
              id:this.$store.getters.getsId
            }).then(function (res) {
              console.log(res.data)
              if(res.data.data==1)
              {
                that.appList = []
              }
              else
              {
                let s = res.data.data
                that.appList = []
                for(let i=0;i<s.length;i++)
                {
                  that.appList.push({
                    id:s[i].id,
                    name:s[i].name,
                    userid:s[i].userid,
                    member:s[i].member,
                    stage:s[i].stage,
                    auth:s[i].auth,
                    result:s[i].result,
                    ctime:s[i].ctime,
                    status:s[i].status,
                  })
                }
              }
              that.totalPage =Math.ceil(that.appList.length/that.pageSize)
              that.totalPage=that.totalPage==0?1:that.totalPage
              that.setCurrentPageDate()
            })
          }
      }
    }
</script>

<style scoped>
@import "../../../common/css/common.css";
</style>
