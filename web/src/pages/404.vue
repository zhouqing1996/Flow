<template>
  <div>
    <p class="p404">404</p>
    <p>未找到该地址！</p>
    <ul>
      <li>
        <a style="cursor:pointer" onclick="history.back()">返回上一级</a>
      </li>
      <!--<li>-->
        <!--<router-link to="/u/question">首页</router-link>-->
      <!--</li>-->
    </ul>
  </div>
</template>

<script>
    export default {
        name: "Notfound"
    }
</script>

<style scoped>
  .p404{
    font-size: 150px;
    font-weight: bold;
    letter-spacing: 5px;
    text-align: center;
    font-family: "Times New Roman";
  }
  ul {
    display: flex;
    flex-flow: row nowrap;
    justify-content: center;
  }
  ul li {
    list-style: none;
    text-align: center;
    line-height: 30px;
    padding: 10px;
    height: 30px;
    width: 100px;
    margin: 0 10px;
  }
</style>
