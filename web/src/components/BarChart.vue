<template>
  <div>
    <div id="BarChart" :style="{height: '400px',width:'80%'}"></div>
  </div>
</template>

<script>
    export default {
        name: "BarChart",
      data(){
          return{
            Bx:[],
            By:[],
            Btitle:''
          }
      },
      props:["data","title"],
      mounted(){
        this.init()
      },
      watch:{
        x(oldVal, newVal) {
          this.drawBar();
        },
        y(oldVal, newVal) {
          this.drawBar();
        },
        title(oldVal, newVal) {
          this.drawBar();
        },
      },
      methods:{
          init:function () {
            this.drawBar()
          },
        ToData:function(data,t)
        {
          this.Btitle = t;
          this.Bx = [];
          this.By =[];
          if (data != null) {
            data.forEach((value, index) => {
              this.Bx.push(value.x);
              this.By.push(value.y);
            });
          }
        },
        drawBar:function () {
            this.ToData(this.data,this.title)
          let myChart = this.$echarts.init(
            document.getElementById("BarChart")
          );
          myChart.setOption({
            color:['#3398D8'],
            tooltip:{
              trigger:'axis',
              axisPointer:{
                type:'shadow'
              }
            },
            grid:{
              left: '3%',
              right: '4%',
              bottom: '3%',
              containLabel: true
            },
            xAxis: [
              {
                type: 'category',
                data: this.Bx,
                axisTick: {
                  alignWithLabel: true
                }
              }
            ],
            yAxis: [
              {
                type: 'value'
              }
            ],
            series: [
              {
                name: '直接访问',
                type: 'bar',
                barWidth: '60%',
                data: this.By
              }
            ]
          })
        }
      }
    }
</script>

<style scoped>

</style>
