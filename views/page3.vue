<template>
  <main>
    <!-- <modal-component ref="modalChild" @data-update="getData" @dataflag-update="dataFlagUpdate"></modal-component> -->

    <main-header-component :grade="fields.grade" :linkname="fields.linkName" :dataflag="fields.dataFlag" @open-modal="openModal"></main-header-component>

    <div class="main-contena">
      <div class="form-contena data">
        <div class="left-contena">
          <div class="select-contena">
            <select id="select" v-model="fields.selectMode" @change="changeSelectArea">
              <option value="1">欠席者リスト</option>
              <option value="2">出欠席表</option>
            </select>
          </div>
        </div>
        <div class="right-contena">
          <div v-if="fields.selectMode === '1'" class="mode01">
            <ul>
              <li class="link-button first small change-width-small"><a href="" @click.prevent.stop="storeData">保存</a></li>
            </ul>
          </div>
          <div v-else-if="fields.selectMode === '2'" class="mode02">
            <div class="guide-box">
              <div class="type01"></div>
              <p>出席</p>
            </div>
            <div class="guide-box">
              <div class="type02"></div>
              <p>遅刻</p>
            </div>
            <div class="guide-box">
              <div class="type03"></div>
              <p>欠席(連絡有)</p>
            </div>
            <div class="guide-box">
              <div class="type04"></div>
              <p>欠席(連絡無)</p>
            </div>
          </div>
        </div>
      </div>
      <div class="data-box" v-if="fields.dataFlag">
        <table v-if="fields.selectMode === '1'" class="mode01">
          <thead>
            <tr>
              <th class="date-column">日付</th>
              <th class="team-column">班</th>
              <th class="number-column">学籍番号</th>
              <th class="name-column">氏名</th>
              <th class="name-column">テーマ名</th>
              <th class="remarks-column">備考</th>
              <th class="remarks-column">欠席連絡</th>
              <th class="remarks-column">補習・再実験日の伝達</th>
              <th class="remarks-column">伝達メールへの返信</th>
              <th class="remarks-column">補習・再実験の手続き</th>
              <th>届出日</th>
              <th>補習・再実験日</th>
              <th>出席</th>
            </tr>
          </thead>
          <tbody>
            <template v-if="dataList.length > 0 && fields.loadFlag">
              <template v-for="(data, index1) in dataList">
                <tr v-for="(user, index2) in data.user" :key="index2">
                  <td v-if="index2 === 0" :rowspan="data.user.length">{{ data.date.split('-')[1] }}月{{ data.date.split('-')[2] }}日</td>
                  <td>{{ user.team }}</td>
                  <td>{{ user.number }}</td>
                  <td>{{ user.name }}</td>
                  <td>増幅回路</td>
                  <td class="input-area"><input type="text" v-model="user.newRemarks"></td>
                  <td class="select-area">
                    <select v-model="user.newCheck01">
                      <option value='0'>なし</option>
                      <option value='1'>メール</option>
                      <option value='2'>電話</option>
                    </select>
                  </td>
                  <td class="check-area" @click="changeCheckArea(index1, index2, 'newCheck02')">{{ setCircule(user.newCheck02) }}</td>
                  <td class="check-area" @click="changeCheckArea(index1, index2, 'newCheck03')">{{ setCircule(user.newCheck03) }}</td>
                  <td class="check-area" @click="changeCheckArea(index1, index2, 'newCheck04')">{{ setCircule(user.newCheck04) }}</td>
                  <td class="date">
                    {{ user.newDate01.split('-')[1] }} / {{ user.newDate01.split('-')[2] }}
                    <input type="date" v-model="user.newDate01" @change="changeDateArea">
                  </td>
                  <td class="date">
                    {{ user.newDate02.split('-')[1] }} / {{ user.newDate02.split('-')[2] }}
                    <input type="date" v-model="user.newDate02" @change="changeDateArea">
                  </td>
                  <td class="check-area" @click="changeCheckArea(index1, index2, 'newCheck05')">{{ setCircule(user.newCheck05) }}</td>
                </tr>
              </template>
            </template>
          </tbody>
        </table>
        <table v-if="fields.selectMode === '2'" class="mode02">
          <thead>
            <tr>
              <th v-if="fields.grade !== '1EC'"></th>
              <th class="number-column"></th>
              <th class="name-column"></th>
              <th class="date-column" v-for="(date, index) in dateArray" :key="index">{{ date.split('-')[1] }}月{{ date.split('-')[2] }}日</th>
            </tr>
          </thead>
          <tbody>
            <template v-if="fields.loadFlag">
              <template v-for="data in users">
                <tr v-for="(user, index1) in data.user" :key="user.id">
                  <td v-if="fields.grade !== '1EC' && index1 === 0" :rowspan="data.user.length" :style="lineHeightChange(data.user.length)">{{ data.team }}</td>
                  <td>{{ user.number }}</td>
                  <td>{{ user.name }}</td>
                  <td v-for="(date, index2) in dateArray" :key="date" :class="{type1: dataTable[user.index][index2] === 1, type2: dataTable[user.index][index2] === 2, type3: dataTable[user.index][index2] === 3, type4: dataTable[user.index][index2] === 4, type5: dataTable[user.index][index2] === 5}"></td>
                </tr>
              </template>
            </template>
          </tbody>
        </table>
      </div>
      <div class="no-data" v-else>
        <div class="icon-contena">
          <i class="fas fa-database"></i>
        </div>
        <p>この機能をつかえません。</p>
        <p>学生情報とスケジュールを登録して、より便利にしましょう。</p>
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
        dataFlag: true,
        selectMode: '1',
        loadFlag: false,
      },
      dateArray: [],
      users: [],
      userData: [],
      themaArray: [],
      tableArray: [],
      dayModeArray: [],
      logs: [],
      dataTable: [],  //出欠席表
      dataList: []  // 欠席者リスト
    }
  },
  data: function() {
    return this.$options.initData();
  },
  mounted: function(){
    this.fields.grade = sessionStorage.getItem('grade');
    this.fields.linkName = sessionStorage.getItem('linkName');
    this.$nextTick(function () {
      this.getUserData();
    });
  },
  methods: {
    openModal: function(func){
      this.$refs.modalChild.openModal(this.fields.grade, this.fields.linkName, func);
    },
    resetData: function(){
      Object.assign(this.$data, this.$options.initData());
    },
    getUserData: function(){
      if(this.fields.grade === '1EC'){
        axios.get(`http://localhost:${port}/dbc.php`,{
          params: {
            mode: '1',
            grade: this.fields.grade
          }
        }).then((res) => {
          if(res.data.param.length > 0){
            this.users = res.data.param;
            this.getScheduleData();
          }
        });
      }else{
        axios.get(`http://localhost:${port}/dbc.php`,{
          params: {
            mode: '2',
            grade: this.fields.grade
          }
        }).then((res) => {
          if(res.data.param.length > 0){
            var count = 0;
            res.data.param.forEach((val1, index1) => {
              var num_front = '';
              var num_front_index = 0;
              val1.user.forEach((val2, index2) => {
                if(num_front !== val2.number.substr(0, 2)){
                  num_front = val2.number.substr(0, 2);
                  num_front_index = index2;
                }
              });
              val1.user = val1.user.slice(num_front_index).concat(val1.user.slice(0, num_front_index));
              this.userData.push(...val1.user);
              val1.user.forEach((val3, index) => {
                val3.index = count;
                count++;
              });
            });
            this.users = res.data.param;

            this.getScheduleData();
          }
        });
      }
    },
    getScheduleData: function(){
      axios.get(`http://localhost:${port}/dbc2.php`,{
        params: {
          grade: this.fields.grade
        }
      }).then((res) => {
        if(res.data.param.length > 0){
          var data = JSON.parse(res.data.param[0].table_json);
          this.dateArray = data.date;
          this.themaArray = data.thema;
          this.tableArray = data.table;
          this.dayModeArray = data.daymode;
          this.getLogData();
        }
      });
    },
    getLogData: function(){
      axios.get(`http://localhost:${port}/dbc4.php`).then((res) => {
        if(res.data.param.length > 0){
          this.logs = res.data.param;

          var start_date = this.setTime('17:50:00');
          var stop_date = this.setTime('18:10:00');

          if(this.fields.grade === '1EC'){
            // 出席テーブルの初期化
            this.users.forEach(val1 => {
              var array = [];
              this.dateArray.forEach((val2, index) => {
                if(this.dayModeArray[index] === '1'){   // 0: 未入力, 1: 出席, 2: 欠席, 3: 遅刻, 4: 実験なし
                  if(Number(val1.number.slice(-3)) % 2 === 1){
                    array.push(0);
                  }else{
                    array.push(4);
                  }
                }else if(this.dayModeArray[index] === '2'){
                  if(Number(val1.number.slice(-3)) % 2 === 0){
                    array.push(0);
                  }else{
                    array.push(4);
                  }
                }else if(this.dayModeArray[index] === '3'){
                  array.push(0);
                }
              });
              this.dataTable.push(array);
            });
  
            // 出席テーブルに設定
            this.logs.forEach(val1 => {
              this.users.forEach((val2, index2) => {
                this.dateArray.forEach((val3, index3) => {
                  if(val2.number === val1.number){
                    if(val1.created_at.split(' ')[0] === val3){
                      var create_date = this.setTime(val1.created_at.split(' ')[1]);
                      if(this.dataTable[index2][index3] !== 4){   // 出席日であれば
                        if (start_date <= create_date && create_date <= stop_date) {
                          this.dataTable[index2][index3] = 1;  // 正常
                        }else{
                          this.dataTable[index2][index3] = 3;  // 遅刻
                        }
                      }
                    }
                  }
                  if(this.dataTable[index2][index3] === 0){
                    if(this.setDay(this.getCurrentDay()) > this.setDay(val3)){  // スケジュールの日付が現在より過去のとき
                      this.dataTable[index2][index3] = 2;  // 欠席
                    }
                  }
                });
              });
            });

          }else{

            // 出席テーブルの初期化
            this.userData.forEach((val1, index1) => {
              var array = [];
              this.dateArray.forEach((val2, index2) => {
                var flag = false;   // 実験実施するかのフラグ
                this.tableArray[index2].forEach((val3, index3) => {
                  if(!flag && val3 !== ''){
                    flag = val1.team.includes(val3);
                    if(flag){
                      return;
                    }
                  }
                });
                if(flag){
                  if(this.setDay(this.getCurrentDay()) > this.setDay(val2)){  // スケジュールの日付が現在より過去のとき
                    array.push(2);
                  }else{
                    array.push(0);     // 0: 未入力, 1: 出席, 2: 欠席(連絡なし), 3: 欠席(連絡あり), 4: 遅刻, 5: 実験なし
                  }
                }else{
                  array.push(5);
                }
              });
              this.dataTable.push(array);
            });

            const promise01 = new Promise((resolve01, reject) => {
              // 出席テーブルに設定(出席、遅刻)
              this.logs.forEach((val1, index1) => {
                this.userData.forEach((val2, index2) => {
                  if(val1.number === val2.number){
                    this.dateArray.forEach((val3, index3) => {
                      const promise02 = new Promise((resolve02, reject) => {
                        if(val1.created_at.split(' ')[0] === val3 && this.dataTable[index2][index3] !== 5){   // 出席日であれば
                          var create_date = this.setTime(val1.created_at.split(' ')[1]);
                          if (start_date <= create_date && create_date <= stop_date) {
                            this.dataTable[index2][index3] = 1;  // 正常
                            resolve02();
                          }else{
                            this.dataTable[index2][index3] = 4;  // 遅刻
                            resolve02();
                          }
                        }else{
                          resolve02();
                        }
                      });
                      promise02.then(() => {
                        if(index1 === this.logs.length - 1 && index2 === this.userData.length - 1 && index3 === this.dateArray.length - 1){
                          resolve01();
                        }
                      });
                    });
                  }else{
                    return;
                  }
                });
              });
            });
            promise01.then(() => {
              // 欠席者リストの設定
              axios.get(`http://localhost:${port}/dbc5.php`).then((res) => {

                var sublog = res.data.param;

                this.dateArray.forEach((val1, index1) => {
                  var array1 = [];
                  var array2 = [];
                  this.dataTable.forEach((val2, index2) => {
                    if(val2[index1] === 2 || val2[index1] === 3){
                      array1.date = val1;
                      var user = Object.create(this.userData[index2]);
                      if(sublog.length > 0){
                        sublog.forEach((val3, index3) => {
                          if(val3.number === this.userData[index2].number && val3.date === val1){
                            array2.push({...Object.getPrototypeOf(user), ...this.createDataStruct01(val3), date: val1});
                            sublog.splice(index3, 1);
                            if(val3.check01 !== '0'){
                              val2[index1] = 3;
                            }
                          }
                        });
                      }else{
                        array2.push({...Object.getPrototypeOf(user), ...this.createDataStruct02(), date: val1});
                      }
                    }
                  });
                  if(array2.length > 0){
                    array1.user = array2;
                    this.dataList.push(array1);
                  }
                  if(index1 === this.dateArray.length - 1){
                    this.fields.loadFlag = true;
                  }
                });
              });
            });
          }
        }
      });
    },
    createDataStruct01: function(val){
      return {
        lateRemarks: val.remarks,
        newRemarks: val.remarks,
        lateCheck01: val.check01,
        newCheck01: val.check01,
        lateCheck02: val.check02,
        newCheck02: val.check02,
        lateCheck03: val.check03,
        newCheck03: val.check03,
        lateCheck04: val.check04,
        newCheck04: val.check04,
        lateDate01: val.date01,
        newDate01: val.date01,
        lateDate02: val.date02,
        newDate02: val.date02,
        lateCheck05: val.check05,
        newCheck05: val.check05,
      }
    },
    createDataStruct02: function(){
      return {
        lateRemarks: null,
        newRemarks: null,
        lateCheck01: '0',
        newCheck01: '0',
        lateCheck02: '0',
        newCheck02: '0',
        lateCheck03: '0',
        newCheck03: '0',
        lateCheck04: '0',
        newCheck04: '0',
        lateDate01: '',
        newDate01: '',
        lateDate02: '',
        newDate02: '',
        lateCheck05: '0',
        newCheck05: '0',
      }
    },
    storeData: function(){
      var createData = [];
      var updateData = [];
      this.dataList.forEach((val1, index1) => {
        val1.user.forEach((val2, index2) => {
          const promise03 = new Promise((resolve03, reject) => {
            if(val2.lateRemarks !== val2.newRemarks || val2.lateCheck01 !== val2.newCheck01 || val2.lateCheck02 !== val2.newCheck02 || val2.lateCheck03 !== val2.newCheck03 || val2.lateCheck04 !== val2.newCheck04 || val2.lateDate01 !== val2.newDate01 || val2.lateDate02 !== val2.newDate02 || val2.lateCheck05 !== val2.newCheck05){
              var array = {...Object.getPrototypeOf(val2), ...val2, date: val1.date};
              if(val2.lateRemarks === null){ // 一つがnullならデータがまだない(作成処理)
                createData.push(array);
                resolve03();
              }else{ //すでにデータがある(更新処理)
                updateData.push(array);
                resolve03();
              }
            }else{
              resolve03();
            }
          });
          promise03.then(() => {
            if(index1 === this.dataList.length - 1 && index2 === val1.user.length - 1){
              const promise04 = new Promise((resolve04, reject) => {
                if(createData.length > 0){
                  console.log(createData);
                  axios.post(`http://localhost:${port}/dbc5.php`,{
                    func: 'create',
                    data: createData
                  }).then((res) => {
                    console.log('作成完了');
                    resolve04();
                  });
                }else{
                  resolve04();
                }
              });
              promise04.then(() => {
                if(updateData.length > 0){
                  axios.post(`http://localhost:${port}/dbc5.php`,{
                    func: 'update',
                    data: updateData
                  }).then((res) => {
                    console.log('更新完了');
                  });
                }
              });
            }
          });
        });
      });
    },
    getCurrentDay: function(){
      var date = new Date();
      var toDoubleDigits = function(num) {
        num += "";
        if (num.length === 1) {
          num = "0" + num;
        }
      return num;     
      };
      var year = date.getFullYear();
      var month = toDoubleDigits(date.getMonth() + 1);
      var day = toDoubleDigits(date.getDate());
      return String(year) + "-" + String(month) + "-" + String(day);
    },
    setDay: function(time){
      var date = new Date(time);
      return date;
    },
    setTime: function(time){
      var date = new Date();

      var hour_minute_second = time.split(":");
      var hour = hour_minute_second[0];
      var minute = hour_minute_second[1];
      var second = hour_minute_second[2];
      date.setHours(Number(hour));
      date.setMinutes(Number(minute));
      date.setSeconds(Number(second));

      return date;  
    },
    changeSelectArea: function(){
      if(this.fields.selectMode === 2){
        
      }
    },
    changeCheckArea: function(index1, index2, key){
      this.fields.loadFlag = false;  // 再レンダリングして監視させる
      if(this.dataList[index1].user[index2][key] === '0'){
        this.dataList[index1].user[index2][key] = '1';
      }else{
        this.dataList[index1].user[index2][key] = '0';
      }
      this.fields.loadFlag = true;
    },
    changeDateArea: function(){
      this.fields.loadFlag = false;  // 再レンダリングして監視させる
      this.fields.loadFlag = true;
    },
    setCircule: function(data){
      if(data === '1'){
        return '◯';
      }else{
        return '';
      }
    },
    lineHeightChange: function(number){
      return "line-height:" + 22 * number + "px;";
    },
    dataFlagUpdate: function(){
      this.fields.dataFlag = false;
    }
  }
}
</script>

      