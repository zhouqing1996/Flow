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
      <el-row :gutter="20">
        <el-col :span="4">
          <div class="addItem" @click="addItem">文本类</div>
          <div class="addItem" @click="addDocItem">文件类</div>
          <div class="addItem" @click="addLongItem">长文类</div>
          <div>
            <el-button @click="Submit" class="submitItem">提交申请</el-button>
          </div>
        </el-col>
        <el-col :span="16">
          <div class="subcontent">
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
                  <el-upload
                    action=""
                    :before-upload="beforeUpload"
                    :on-preview="handlePreview"
                    :on-remove="handleRemove"
                    :on-success="handleSuccess"
                    multiple
                    :limit="1"
                    :http-request="uploadfile"
                    :on-change="onchange">
                    <el-button size="small" type="primary" >上传</el-button>
                    <div slot="tip" class="el-upload__tip">最多上传1个文件,文件必须是pdf!</div>
                  </el-upload>
                  <el-button @click="deleteDocItem(index)" class="deleteItem">删除项目</el-button>
                </el-form-item>
              </el-col>
            </el-form>
            <el-form v-if="createList.longText.length>0">
              <el-col :span="16">
                <el-form-item v-for="(item,index) in createList.longText" :key="index">
                  <el-input v-model="item.fname" placeholder="名称" style="width: 100px"></el-input>
                  <el-input type="textarea" v-model="item.fcontent" placeholder="长文本内容" style="width: 300px"></el-input>
                  <el-button @click="deleteLongItem(index)" class="deleteItem">删除项目</el-button>
                </el-form-item>
              </el-col>
            </el-form>
          </div>
        </el-col>
      </el-row>
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
            longText:[],
          },
          //  文件
          fileList: [],
          filename:[],
          filedir:[],
        }
      },
      created() {
          this.createList.name = this.$route.query.name
        this.createList.member = this.$route.query.member
      },
      methods:{
        beforeUpload (file) {
          let fd = new FormData()
          fd.append('file', file)
          let that = this
          that.$http.post('/flow/flow/uploadfile', fd).then(function (res) {
            console.log(res)
            if(res.data.data==0)
            {
              fd.delete('file')
              that.$alert('请上传pdf格式的文件', '警告', {
                confirmButtonText: '确定',})
            }
            else
            {
              that.filename.push(res.data.data[1])
              that.filedir.push(res.data.data[2])
            }
          })
        },
        // 点击移除文件按钮触发
        handleRemove (file, fileList) {
          console.log(file,fileList)
        },
        handlePreview (file) {
          console.log(file)
        },
        handleSuccess (index) {
        },
        // 覆盖默认的提交动作
        uploadfile (id) {
        },
        // 文件上传成功可触发
        onchange (index) {
        },
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
            filename:'',
            filedir:''
          })
        },
        deleteDocItem:function (id) {
          this.createList.doc.splice(id,1)
          this.filename.splice(id,1)
          this.filedir.splice(id,1)
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
            console.log(this.createList)
          for(let i=0;i<this.filedir.length;i++)
          {
            this.createList.doc[i]['filename']=this.filename[i]
            this.createList.doc[i]['filedir']=this.filedir[i]
          }
          if(this.createList.content==null || (this.createList.content!=null &&this.createList.content.length==0))
          {
            if(this.createList.doc==null || (this.createList.doc!=null &&this.createList.doc.length==0))
            {
              if(this.createList.longText==null || (this.createList.longText!=null &&this.createList.longText.length==0))
              {
                this.$alert('请填写审批内容', '提示', {
                  confirmButtonText: '确定',
                  type:'warning',
                  center: true
                })
              }
            }
          }
          else
          {
            let that =this
            that.$http.post('/flow/flow/createflow',{
              name:that.createList.name,
              userid:that.createList.uid,
              member:that.createList.member,
              content:that.createList.content,
              doc:that.createList.doc,
              longText:that.createList.longText
            }).then(function (res) {
              console.log(res.data)
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
