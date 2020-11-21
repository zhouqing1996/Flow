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
      <div v-if="currentPageData.length>0">
        <table>
          <th>审批编号</th>
          <th>审批名称</th>
          <th>申请人</th>
          <th>申请时间</th>
          <th>审批阶段</th>
          <th>当前审批人</th>
          <th>审批意见</th>
          <th>操作</th>
          <tr v-for="(x,index) in currentPageData">
            <td>{{x.id}}</td>
            <td>{{x.name}}</td>
            <td>{{x.userid}}</td>
            <td>{{x.ctime}}</td>
            <td>{{x.stage}}</td>
            <td>{{x.auth}}</td>
            <td>{{x.result}}</td>
            <td>
              <span class="download" @click="downloadFile(x.id)">下载</span>
              <span class="look" @click="lookManage(x.id)">查看</span>
              <span @click="dialogPend=true">审批</span>
              <el-dialog title="审批意见" :visible.sync="dialogPend">
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
                <div slot="footer" style="align-content: center" class="dialog-footer">
                  <el-button type="primary" @click="submitManage(x.id)">提交</el-button>
                  <el-button @click="reset">重置</el-button>
                </div>
              </el-dialog>
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
        没有待审批的文件！
      </div>
    </div>
</template>

<script>
    export default {
        name: "pendManage",
      data() {
        return{
          pendList:[],
          uid:this.$store.getters.getsId,
          dialogPend:false,
          OpiniodList:{
            flow:'',
            other:''
          },
          // 翻页相关
          currentPage: 1,
          totalPage: 1,
          pageSize: 10,
          currentPageData:[]
        }
      },
      created(){
          this.getPendList()
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
          this.currentPageData = this.pendList.slice(begin, end)
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
        submitManage:function (mid) {
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
              mid:mid,
              flow:that.OpiniodList.flow,
              other:that.OpiniodList.other
            }).then(function (res) {
              console.log(res.data)
              if(res.data.message=="审批成功")
              {
                that.getPendList()
                that.dialogPend=false
                that.reset()
              }
            })
          }
        },
        reset:function()
        {
          this.OpiniodList.flow=''
          this.OpiniodList.other=''
        },
          lookManage:function(id)
          {
            //查看申请表，设置待审批时的查看和完成审批时的查看，flag=1表示完成时的审批，flag=2表示待审批时的查看
            this.$router.push({
              path:'/user/manage/lookmanage',
              query:{
                id:id,
                flag:2
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
                that.totalPage =Math.ceil(that.pendList.length/that.pageSize)
                that.totalPage=that.totalPage==0?1:that.totalPage
                that.setCurrentPageDate()
              }
            })
          }
      }
    }
</script>

<style scoped>
@import "../../../common/css/common.css";
</style>
