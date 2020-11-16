<template>
<div>
  <div id="calenderHeatmap" :style="{height:'300px',width:'60%'}"></div>
</div>
</template>

<script>
    export default {
        name: "CalenderHeatmap",
      data(){
        var dates = new Date()
        var years = dates.getFullYear()
          return{
            data:[],
            nyear:years,
            htitle:'',
            maxNum:0
          }
      },
      props:['heatmap','title'],
      mounted(){
          this.init()
      },
      watch: {
        heatmap(oldVal, newVal) {
          this.CalenderMap();
        },
        title(oldVal, newVal) {
          this.CalenderMap();
        }
      },
      methods:{
          init:function(){
          //  初始化
            this.CalenderMap()
            console.log(this.htitle)
          },
        toData:function(year,datas,stitle){
            let that =this
          that.htitle = stitle
            for(let i=0;i<datas.length;i++)
            {
                that.data.push([
                  datas[i].dates,datas[i].num
                ])
              if(datas[i].num>that.maxNum)
              {
                that.maxNum = datas[i].num
              }
            }
          console.log(that.data)
        },
        CalenderMap:function () {
            let that =this
          console.log(that.heatmap)
          that.toData(that.nyear,that.heatmap,that.title)
          let map = that.$echarts.init(document.getElementById('calenderHeatmap'))
          map.setOption({
            grid: {
              x: 20,
              y: 50,
              x2: 10,
              y2: 10,
              height: '200px',
              width:'300px'
            },
            title:{
              top:30,
              left:'left',
              text:that.htitle
            },
            tooltip: {},
            visualMap:{
              min:0,
              max:that.maxNum,
              minInterval:1,
              type:'piecewise',
              orient:'horizontal',
              left:'center',
              top:65,
              textStyle:{
                color:'#000'
              }
            },
            calendar:{
              top:120,
              left: 30,
              right: 30,
              cellSize: ['auto', 13],
              range: that.nyear,
              itemStyle: {
                borderWidth: 0.5
              },
              yearLabel: {show: false}
            },
            series: {
              type: 'heatmap',
              coordinateSystem: 'calendar',
              data:that.data
            }
          })
        },
      }
    }
</script>

<style scoped>

</style>
