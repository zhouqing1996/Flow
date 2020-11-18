<template>
  <div>
    <div>
      <el-breadcrumb separator="/">
        <el-breadcrumb-item>申请管理</el-breadcrumb-item>
        <el-breadcrumb-item>新建申请(申请内容)</el-breadcrumb-item>
      </el-breadcrumb>
      <el-divider></el-divider>
    </div>
    <div>
      <div class="title">
        <div><strong>申请表名称：</strong>{{createList.name}}</div>
        <div><strong>申请人：</strong>{{createList.uname}}</div>
      </div>
      <div class="content">
        <div>
          <el-col :span="4">
            <div class="addItem" @click="addItem">文本类</div>
            <div class="addItem" @click="addDocItem">文件类</div>
            <div class="addItem" @click="addLongItem">长文类</div>
            <div>
              <el-button @click="Submit" class="submitItem">提交申请</el-button>
            </div>
          </el-col>
        </div>
        <el-col :span="2"></el-col>
        <div>
          <el-form v-if="createList.content.length>0">
            <el-col :span="16">
              <el-form-item v-for="(item,index) in createList.content" :key="index">
                <el-input v-model="item.fname" placeholder="名称" style="width: 100px"></el-input>
                <el-input v-model="item.fcontent" placeholder="文本内容" style="width: 300px"></el-input>
                <el-button @click="deleteItem(index)" class="deleteItem">删除项目</el-button>
              </el-form-item>
            </el-col>
          </el-form>
          <el-form v-if="createList.doc.length>0">
            <el-col :span="16">
              <el-form-item v-for="(item,index) in createList.doc" :key="index">
                <el-input v-model="item.fname" placeholder="名称" style="width: 100px"></el-input>
                <el-input type="file" v-model="item.fcontent" placeholder="文件内容" style="width: 300px" ></el-input>
                <el-button @click="deleteDocItem(index)" class="deleteItem">删除项目</el-button>
              </el-form-item>
            </el-col>
          </el-form>
          <el-form v-if="createList.longText.length>0">
            <el-col :span="16">
              <el-form-item v-for="(item,index) in createList.longText" :key="index">
                <el-input v-model="item.fname" placeholder="名称" style="width: 100px"></el-input>
                <el-input v-model="item.fcontent" placeholder="长文本内容" style="width: 300px"></el-input>
                <el-button @click="deleteLongItem(index)" class="deleteItem">删除项目</el-button>
              </el-form-item>
            </el-col>
          </el-form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        name: "CreateApp2",
      data() {
        return{
          createList:{
            uid:this.$store.getters.getsId,
            uname:this.$store.getters.getsName,
            name:'',
            member:[],
            content:[],
            doc:[],
            longText:[]
          }
        }
      },
      created() {
          this.createList.name = this.$route.query.name
        this.createList.member = this.$route.query.member
      },
      methods:{
          addItem:function () {
            this.createList.content.push({
              fname:'',
              fcontent:''
            })
          },
        deleteItem:function (id) {
            this.createList.content.splice(id,1)
        },
        addDocItem:function () {
          this.createList.doc.push({
            fname:'',
            fcontent:''
          })
        },
        deleteDocItem:function (id) {
          this.createList.doc.splice(id,1)
        },
        addLongItem:function () {
          this.createList.longText.push({
            fname:'',
            fcontent:''
          })
        },
        deleteLongItem:function (id) {
          this.createList.longText.splice(id,1)
        },
        Submit:function () {
          if(this.createList.content==null || (this.createList.content!=null &&this.createList.content.length==0))
          {
            console.log(this.createList.content)
            this.$alert('请填写审批内容', '提示', {
              confirmButtonText: '确定',
              type:'warning',
              center: true
            })
          }
          else
          {
            let that =this
            that.$http.post('/flow/flow/createflow',{
              name:that.createList.name,
              userid:that.createList.uid,
              member:that.createList.member,
              content:that.createList.content,
            }).then(function (res) {
              if(res.data.data==1)
              {
                that.$router.push({
                  path:'/user/application/look'
                })
              }
              alert(res.data.message)
            })
          }
        }
      },
    }
</script>

<style scoped>
@import "../../../common/css/create.css";
</style>
