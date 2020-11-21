<template>
    <div>
      <div>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item>申请管理</el-breadcrumb-item>
          <el-breadcrumb-item>新建申请</el-breadcrumb-item>
        </el-breadcrumb>
        <el-divider></el-divider>
      </div>
      <div>
<!--        设计思路：用户创建申请，设定名称，选择审批人列表，（用户id,自己排序）,初始审批人为空，系统获取当前时间，-->
        <el-form :model="flowList">
          <el-form-item label="申请表名称：" >
            <el-input style="width: 350px;" v-model="flowList.name" auto-complete="off"></el-input>
          </el-form-item>
          <el-form-item label="申请人：" >
            <el-input style="width: 350px;" v-model="flowList.uname" auto-complete="off" disabled></el-input>
          </el-form-item>
          <el-form-item label="申请人列表：" >
            <el-select style="width: 350px;" v-model="flowList.member" multiple placeholder="请选择审批人" auto-complete="off">
              <el-option v-for="(x,i) in List" :label="x.username" :value="x.id" :key="i">{{x.username}}</el-option>
            </el-select>
          </el-form-item>
        </el-form>
        <button @click="goApplication">填写申请表</button>
      </div>
    </div>
</template>

<script>
    export default {
        name: "CreateApp",
      data()
      {
        return{
          //申请表信息
          flowList: {
            name:'',
            uid: this.$store.getters.getsId,
            uname:this.$store.getters.getsName,
            member:[],
          },
          List:[]
        }
      },
      created() {
          this.getList()
      },
      methods:{
        getList:function () {
          let that = this
          that.$http.post('/home/user/userlist',{
            id:that.flowList.uid
          }).then(function (res) {
            console.log(res.data)
            that.List = res.data.data
          })
        },
        goApplication:function () {
          if(this.flowList.name=='')
          {
            this.$alert('请填写申请表名称', '提示', {
              confirmButtonText: '确定',
              type:'warning',
              center: true
            })
          }
          else if(this.flowList.member==null || (this.flowList.member!=null &&this.flowList.member.length==0))
          {
            console.log(this.flowList.member)
            this.$alert('请选择审批人', '提示', {
              confirmButtonText: '确定',
              type:'warning',
              center: true
            })
          }
          else
          {
            this.$router.push({
              path:'/user/application/created',
              query:{
                name:this.flowList.name,
                member:this.flowList.member
              }
            })
          }

        }
      }
    }
</script>

<style scoped>

</style>
