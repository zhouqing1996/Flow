<template>
  <div>
    <div>
      <el-breadcrumb separator="/">
        <el-breadcrumb-item>主页</el-breadcrumb-item>
      </el-breadcrumb>
      <el-divider></el-divider>
    </div>
    <div class="intro">
      <p>系统简介：公文流转系统用于处理日常工作中企业内外部各种公文，包括公文的拟稿、审批、传阅、公告和归档。</p>
      <p>系统功能：对公文的拟稿、催办、流转、传阅、公告、归档进行电子化管统，方便灵活的定义公文流程，使之满足企业个性化需求；</p>
      <p> 支持公开公文和私密公文，对于保密级别较高的公文，即使是模组管理者也不能看到； </p>
      <p>支持文本正文、Word正文，电子签章、手写签名功能，可以直接编辑成正式公文格式，满足各种撰写和审批需求；</p>
      <p>提供发文号功能，方便机关单位对公文的管理，发布后的公文只能浏览不可修改，公文更具法律效力；</p>
      <p>完善的提醒机制和跨网特性，短信、邮件的提醒方式让公文审批流转过程更顺畅；</p>
      <p>支持公文分类管理，不同部门可应用不同类别的公文，并且不同类型的公文支持特有的签核流程；</p>
      <p>对每次修改的公文保存留档，并提供方便的版本比对工具.</p>
    </div>
  </div>
</template>

<script>
    export default {
        name: "userIndex"
    }
</script>

<style scoped>
@import "../../common/css/index.css";
</style>
