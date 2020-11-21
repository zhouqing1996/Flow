<template>
    <div>
<!--      用户管理-->
      <div>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{ path: '/admin/index' }">首页</el-breadcrumb-item>
          <el-breadcrumb-item>
            <span @click="getUserList" style="font-weight: bold;color: #2c3e50">用户列表</span>
          </el-breadcrumb-item>
        </el-breadcrumb>
        <el-divider></el-divider>
      </div>
      <div>
        <button @click="dialogAdd=true">添加用户</button>
        <el-dialog title="添加用户" :visible.sync="dialogAdd">
          <el-form :model="addUser">
            <el-form-item label="用户工号：" >
              <el-input style="width: 350px;" v-model="addUser.no" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="用 户 名：" >
              <el-input style="width: 350px;" v-model="addUser.name" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="用户身份：" >
              <el-select style="width: 350px;" v-model="addUser.role" auto-complete="off">
                <el-option :value="x.id" v-for="(x,i) in roleList":label="x.name" :key="i">{{x.name}}</el-option>
              </el-select>
            </el-form-item>
          </el-form>
          <div slot="footer" style="align-content: center" class="dialog-footer">
            <el-button type="primary" @click="addUserD">提交</el-button>
            <el-button @click="reset">重置</el-button>
          </div>
        </el-dialog>
      </div>
      <div>
        <table>
          <th>编号</th>
          <th>用户工号</th>
          <th>用户名</th>
          <th>用户密码</th>
          <th>用户角色</th>
          <th>用户邮箱</th>
          <th>操作</th>
          <tr v-for="(x) in currentPageData">
            <td>{{x.id}}</td>
            <td>{{x.no}}</td>
            <td>{{x.username}}</td>
            <td>{{x.password}}</td>
            <td>{{x.role}}</td>
            <td>{{x.email}}</td>
            <td>
              <span class="change" @click="resetPass(x.id)">重置密码</span>
              <span class="delete" v-if="x.status==1&&x.id!=1" @click="deleteUser(x.id,1)">删除</span>
              <span class="delete" v-if="x.status==0" @click="deleteUser(x.id,2)">生效</span>
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
        name: "User",
      data()
      {
        return{
          userList:[],
          //添加用户
          dialogAdd:false,
          addUser:{
            no:0,
            name:'',
            role:'',
          },
          roleList:[],
          // 翻页相关
          currentPage: 1,
          totalPage: 1,
          pageSize: 10,
          currentPageData:[]
        }
      },
      created() {
          this.getRoleList()
          this.getUserList()
      },
      methods:{
          getRoleList:function()
          {
            let that = this
            that.$http.post('/home/user/getrole').then(function (res) {
              that.roleList = []
              that.roleList = res.data.data
            })
          },
          getUserList:function () {
            let that = this
            that.$http.post('/home/user/query',{
              flag:1
            }).then(function (res) {
              console.log(res.data)
              let list = res.data.data
              that.userList = []
              for(let i=0;i<list.length;i++)
              {
                that.userList.push({
                  id:list[i].id,
                  no:list[i].no,
                  username:list[i].username,
                  password:list[i].password,
                  role:list[i].role,
                  status:list[i].status,
                  email:list[i].email
                })
              }
              that.totalPage =Math.ceil(that.userList.length/that.pageSize)
              that.totalPage=that.totalPage==0?1:that.totalPage
              that.setCurrentPageDate()
            }).catch(function (error) {
              console.log(error)
            })
          },
        resetPass:function (id) {
          let that =this
          that.$http.post('/home/index/resetpass',{
            id:id
          }).then(function (res) {
            console.log(res.data)
            that.getUserList()
          })
        },
        deleteUser:function (id,flag) {
            let that = this
          that.$http.post('/home/user/deleteuser',{
            id:id,
            flag:flag
          }).then(function (res) {
            console.log(res.data)
            if(res.data.message=="成功")
            {
              that.getUserList()
            }
            // alert(res.data.message)
          })
        },
        //添加用户
        addUserD:function()
        {
          let that =this
          that.$http.post('/home/user/adduser',{
            no:that.addUser.no,
            name:that.addUser.name,
            role:that.addUser.role
          }).then(function (res) {
            console.log(res.data)
            if(res.data.message=='添加成功')
            {
              that.getUserList()
              that.dialogAdd =false
              that.reset()
            }
            else
            {
              alert(res.data.message)
            }
          })
        },
        reset:function()
        {
          this.addUser.no=''
          this.addUser.name=''
          this.addUser.role=''
        },

        //分页
        setCurrentPageDate: function () {
          let begin = (this.currentPage - 1) * this.pageSize;
          let end = this.currentPage * this.pageSize;
          this.currentPageData = this.userList.slice(begin, end)
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
      },

    }
</script>

<style scoped>
@import "../../common/css/user.css";
</style>
