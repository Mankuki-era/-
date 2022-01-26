<template>
  <main>
    <modal-component ref="modalChild" @data-update="getData" @dataflag-update="dataFlagUpdate"></modal-component>

    <main-header-component :grade="fields.grade" :linkname="fields.linkName" :dataflag="fields.dataFlag" @open-modal="openModal"></main-header-component>

    <div class="main-contena schedule" :class="{Bgadd: fields.dataFlag}">
      <div class="schedule-box" v-if="fields.dataFlag">
        <table>
          <thead>
            <tr>
              <th v-if="fields.grade === '1EC'">登校形態</th>
              <th>日付</th>
              <template v-if="fields.grade !== '2NC'"><th class="thema-name" v-for="(thema, index) in themaArray" :key="index">{{ thema }}</th></template>
              <template v-if="fields.grade === '2NC'"><th class="team-name" v-for="(team, index) in teamArray" :key="index">{{ team }}</th></template>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(date, index1) in dateArray" :key="index1">
              <th v-if="fields.grade === '1EC'">{{ setDayMode(dayModeArray[index1]) }}</th>
              <th>{{ date }}</th>
              <template v-if="fields.grade !== '2NC'"><td v-for="(thema, index2) in themaArray" :key="index2">{{ tableArray01[index1][index2] }}</td></template>
              <template v-if="fields.grade === '2NC'"><td v-for="(team, index2) in teamArray" :key="index2">{{ tableArray02[index1][index2] }}</td></template>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="no-data" v-else>
        <div class="icon-contena">
          <i class="fas fa-calendar-alt"></i>
        </div>
        <p>スケジュールがありません。</p>
        <p>スケジュールを登録して、より便利にしましょう。</p>
      </div>
    </div>
  </main>
</template>

<script>
module.exports = {
  initData: function(){
    return {
      fields: {
        grade: '',
        linkName: '',
        startDate: '',
        dateAmount: 1,
        postDateAmount: 1,
        themaAmount: 1,
        postThemaAmount: 1,
        nextStepFlag: false,
        dataFlag: false,
        editMode: false
      },
      dateArray: [],
      themaArray: [''],
      teamArray: [],
      tableArray01: [],
      tableArray02: [],
      dayModeArray: []
    }
  },
  data: function() {
    return this.$options.initData();
  },
  mounted: function(){
    this.fields.grade = sessionStorage.getItem('grade');
    this.fields.linkName = sessionStorage.getItem('linkName');
    this.getData();
  },
  methods: {
    openModal: function(func){
      this.$refs.modalChild.openModal(this.fields.grade, this.fields.linkName, func);
    },
    resetData: function(){
      Object.assign(this.$data, this.$options.initData());
    },
    getData: function(){
      axios.get(`http://localhost:${port}/dbc2.php`,{
        params: {
          grade: this.fields.grade
        }
      }).then((res) => {
        if(res.data.param.length > 0){
          var data = JSON.parse(res.data.param[0].table_json);
          this.dateArray = data.date;
          this.themaArray = data.thema;
          this.teamArray = data.team;
          this.tableArray01 = data.table;
          this.dayModeArray = data.daymode;
          this.fields.dataFlag = true;
          
          if(this.fields.grade === '2NC'){
            console.log(data)
            for(var i = 0; i < this.dateArray.length; i++){
              this.tableArray02.push([]);
              for(var j = 0; j < this.teamArray.length; j++){
                this.tableArray02[i].push('');
              }
            }
            array = [] // [[0, 4, 1], ...] [[日付のインデックス, 班のインデックス, テーマのインデックス], ...]
            this.tableArray01.forEach((val1, index1) => {
              val1.forEach((val2, index2) => {
                if(val2 !== ''){
                  this.teamArray.forEach((val3, index3) => {
                    if(val2 === val3){
                      array.push([index1, index3, index2])
                    }
                  });
                }
              });
            });
            array.forEach(val => {
              this.tableArray02[val[0]][val[1]] = this.themaArray[val[2]]
            });
          }
        }
      });
    },
    setDayMode: function(number){
      if(number === '1'){
        return '奇数';
      }else if(number === '2'){
        return '偶数';
      }else if(number === '3'){
        return '全員登校';
      };
    },
    dataFlagUpdate: function(){
      this.fields.dataFlag = false;
    }
  }
}
</script>