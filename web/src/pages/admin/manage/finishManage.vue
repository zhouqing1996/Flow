<template>
    <div>
<!--      已完成审批-->
      <div>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item>审批管理</el-breadcrumb-item>
          <el-breadcrumb-item>已审批</el-breadcrumb-item>
        </el-breadcrumb>
        <el-divider></el-divider>
      </div>
      <div v-if="currentPageData.length>0">
        <table>
          <th>审批编号</th>
          <th>审批名称</th>
          <th>申请人</th>
          <th>申请时间</th>
          <th>审批意见</th>
          <th>审批备注</th>
          <th>操作</th>
          <tr v-for="(x,index) in currentPageData">
            <td>{{x.id}}</td>
            <td>{{x.name}}</td>
            <td>{{x.userid}}</td>
            <td>{{x.ctime}}</td>
            <td>{{x.result}}</td>
            <td>{{x.other}}</td>
            <td>
              <span @click="lookFinishManage(x.id)" class="look">查看</span>
              <span class="download" @click="downloadFile(x.id)">下载</span>
            </td>
          </tr>
        </table>
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
      <div v-else>
        没有完成的审批！
      </div>
    </div>
</template>

<script>
    export default {
        name: "finishManage",
      data()
      {
        return{
          // 翻页相关
          currentPage: 1,
          totalPage: 1,
          pageSize: 10,
          currentPageData:[],
          finishList:[],
          uid:this.$store.getters.getsId
        }
      },
      created(){
          this.getFinishList()
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
        //分页
        setCurrentPageDate: function () {
          let begin = (this.currentPage - 1) * this.pageSize;
          let end = this.currentPage * this.pageSize;
          this.currentPageData = this.finishList.slice(begin, end)
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
        getFinishList:function () {
          let that =this
          that.$http.post('/flow/flow/finishmanage',{
            id:that.uid
          }).then(function (res) {
            console.log(res.data)
            that.finishList = []
            that.finishList =res.data.data
            that.totalPage =Math.ceil(that.finishList.length/that.pageSize)
            that.totalPage=that.totalPage==0?1:that.totalPage
            that.setCurrentPageDate()
          })
        },
        lookFinishManage:function (id) {
          this.$router.push({
            path:'/user/manage/lookmanage',
            query:{
              id:id,
              flag:1
            }
          })
        }
      }

    }
</script>

<style scoped>
  @import "../../../common/css/common.css";
</style>
