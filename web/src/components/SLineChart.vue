<template>
  <div>
      <div id="SLineChart" :style="{height: '400px',width:'80%'}"></div>
  </div>
</template>
<script>
  export default {
    name: "SLineChart",
    data() {
      return {
        data: [],
        tyname: [],
        count: [],
        stitle:''
      };
    },
    props: ["all","title"],
    mounted() {
      this.init();
    },
    watch: {
      all(oldVal, newVal) {
        this.drawLine();
      },
      title(oldVal, newVal) {
        this.drawLine();
      }
    },
    methods: {
      init() {
        this.drawLine();
        console.log(this.title)
      },
      drawLine() {
        this.toArray(this.all,this.stitle);
        // 基于准备好的dom，初始化echarts实例
        let myChart = this.$echarts.init(
          document.getElementById("SLineChart")
        );
        // 绘制图表
        myChart.setOption(
          {
            grid: {
              left: '3%',
              right: '4%',
              bottom: '5%',
              height: '200px',
              width:'70%'
            },
            title:{
              top:30,
              left:'left',
              text:this.title
            },
            xAxis: {
              type: "category",
              name:'分类',
              data: this.tyname,
              axisLine: {
                lineStyle: {
                  type: "solid",
                  color: "rgba(37, 37, 46, 0.2)",
                  width: 2
                }
              },
              axisLabel: {
                show: true,
                textStyle: {
                  color: "#25252E"
                }
              }
            },
            yAxis: {
              type: "value",
              minInterval:1,
              splitNumber:5,
              name:'数目',
              splitLine: {
                show: true,
                lineStyle: {
                  type: "dashed",
                  color: "rgba(37, 37, 46, 0.2)"
                }
              },
              axisLine: {
                show: false,
                lineStyle: {
                  type: "solid",
                  color: "rgba(37, 37, 46, 0.2)",
                  width: 1
                }
              },
              axisLabel: {
                show: true,
                textStyle: {
                  color: "#25252E"
                }
              }
            },
            series: [
              {
                data: this.count,
                type: "line",
                smooth: true,
                color: ["#58afed"],
                emphasis: {},
                areaStyle: {
                  normal: {
                    color: new this.$echarts.graphic.LinearGradient(0, 0, 0, 1,
                      [
                        { offset: 0, color: "#0070FF" },
                        { offset: 0.5, color: "#AACFFF" },
                        { offset: 1, color: "#fff" }
                      ]
                    )
                  }
                }
              }
            ],
            tooltip: {
              show: true,
              trigger: "axis"
            }
          },
          true
        );
      },
      toArray(arr,til) { // 对数据进行格式处理
        this.tyname = [];
        this.count = [];
        this.stitle = til
        if (arr != null) {
          arr.forEach((value, index) => {
            this.tyname.push(value.tyname);
            this.count.push(value.num);
          });
        }
      }
    }
  };
</script>

<style scoped>
  .fr{
    float: right;

  }
  .echarts_outer {
    display: inline-block;
    width: 80%;
    height: 600px;
    /*border: 1px solid #d3d3d5;*/
    border-radius: 6px;
  }
  .echarts_div {
    background-color: #fff;
    border-radius: 6px;
  }
  /*.echarts_titles {*/
    /*height: 60px;*/
    /*line-height: 60px;*/
    /*border-bottom: 1px solid #d3d3d5;*/
    /*padding: 0 20px;*/
  /*}*/
  /*.title_left {*/
    /*font-size: 18px;*/
    /*color: #25252e;*/
    /*font-weight: 700;*/
  /*}*/
  /*.echarts_btns {*/
    /*z-index: 100;*/
  /*}*/
  /*.echarts_btn {*/
    /*width: 65px;*/
    /*height: 22px;*/
    /*line-height: 22px;*/
    /*font-size: 12px;*/
    /*background: rgba(0, 110, 251, 0.1);*/
    /*color: #25252e;*/
    /*border-radius: 2px;*/
    /*cursor: pointer;*/
  /*}*/
  /*.echarts_btn:first-child {*/
    /*margin-right: 12px;*/
  /*}*/
  /*.echarts_btn.active_btn {*/
    /*background-color: #006efb;*/
    /*color: #fff;*/
  /*}*/
</style>
