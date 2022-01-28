<template>
  <main>
    <div class="main-contena">
      <div class="form-contena data">
        <div class="left-contena">
          <div class="select-contena">
            <select id="select" v-model="fields.selectMode" @change="changeMode">
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
      <div class="data-box" v-if="fields.usableFlag">
        <table v-if="fields.selectMode === '1' && dataList.length > 0" class="mode01">
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
        <p class="mode01-msg" v-if="fields.selectMode === '1' && dataList.length === 0"><i class="fas fa-info-circle"></i> 欠席者はいません</p>
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
              <template v-if="fields.grade === '1EC'">
                <tr v-for="(user, index1) in userData" :key="user.id">
                  <td>{{ user.number }}</td>
                  <td>{{ user.name }}</td>
                  <td v-for="(date, index2) in dateArray" :key="date" :class="{type1: dataTable[index1][index2] === 1, type2: dataTable[index1][index2] === 2, type3: dataTable[index1][index2] === 3, type4: dataTable[index1][index2] === 4, type5: dataTable[index1][index2] === 5}"></td>
                </tr>
              </template>
              <template v-else>
                <template v-for="data in users">
                  <tr v-for="(user, index1) in data.user" :key="user.id">
                    <td v-if="index1 === 0" :rowspan="data.user.length" :style="lineHeightChange(data.user.length)">{{ data.team }}</td>
                    <td>{{ user.number }}</td>
                    <td>{{ user.name }}</td>
                    <td v-for="(date, index2) in dateArray" :key="date" :class="{type1: dataTable[user.index][index2] === 1, type2: dataTable[user.index][index2] === 2, type3: dataTable[user.index][index2] === 3, type4: dataTable[user.index][index2] === 4, type5: dataTable[user.index][index2] === 5}"></td>
                  </tr>
                </template>
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
        usableFlag: false,
        dataFlag: false,
        selectMode: '1',
        loadFlag: false, // レンダリング用Flag
      },
      dateArray: [],
      users: [], // [{team: 'A1', user: Array(4)}, ...]の形
      userData: [], // [{}, {}, {}...]の形
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
    if(sessionStorage.getItem('logSelectMode')){
      this.fields.selectMode = sessionStorage.getItem('logSelectMode');
    }
    this.$nextTick(function () { // DOMの更新後に処理
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
      axios.get(`http://localhost:${port}/backend/dbc1.php`,{
        params: {
          mode: (this.fields.grade === '1EC') ? '1' : '2',
          grade: this.fields.grade
        }
      }).then((res) => {
        if(res.data.param.length > 0){
          if(this.fields.grade === '1EC'){
            this.userData = res.data.param;
          }else{
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
          }
          this.getScheduleData();
        }
      });
    },
    getScheduleData: function(){
      axios.get(`http://localhost:${port}/backend/dbc2.php`,{
        params: {
          grade: this.fields.grade
        }
      }).then((res) => {
        if(res.data.param.length > 0){
          var data = JSON.parse(res.data.param[0].table_json);
          this.dateArray = data.date;
          if(this.fields.grade === '1EC'){
            this.dayModeArray = data.daymode;
          }else{
            this.themaArray = data.thema;
            this.tableArray = data.table;
          }
          this.fields.usableFlag = true; // 機能を使えるFlag
          this.getLogData();
        }
      });
    },
    getLogData: function(){
      axios.get(`http://localhost:${port}/backend/dbc4.php`, {
        params: {
          grade: this.fields.grade
        }
      }).then((res) => {
        var start_time = this.setTime('17:50:00');
        var stop_time = this.setTime('18:10:00');
        this.initDataTable(stop_time);

        if(res.data.param.length > 0){
          this.logs = res.data.param;
          this.setDataTable(start_time, stop_time).then(() => {
            this.getSublogData();
          });
        }else{
          this.getSublogData();
        }
      });
    },
    getSublogData: function(){
      axios.get(`http://localhost:${port}/backend/dbc5.php`, {
        params: {
          grade: this.fields.grade
        }
      }).then((res) => {
        this.initDataList();
        if(res.data.param.length > 0){
          this.setDataList(res.data.param);
        }else{
          this.fields.loadFlag = true;
        }
      });
    },
    initDataTable: function(stop_time){ 
      // 出席テーブルの初期化
      this.userData.forEach((val1, index1) => {
        var array = [];
        this.dateArray.forEach((val2, index2) => {
          var flag = false; // 実験実施するかのフラグ
          if(this.fields.grade === '1EC'){
            if((this.dayModeArray[index2] === '1' && Number(val1.number.slice(-3)) % 2 === 1) || 
               (this.dayModeArray[index2] === '2' && Number(val1.number.slice(-3)) % 2 === 0) ||
               (this.dayModeArray[index2] === '3')){
              flag = true
            }
          }else{
            this.tableArray[index2].forEach((val3, index3) => {
              if(!flag && val3 !== ''){
                flag = val1.team.includes(val3);
                if(flag){
                  return;
                }
              }
            });
          }
          if(flag){
            if(this.setDay(this.getCurrentDay()) > this.setDay(val2) || (this.getCurrentDay() === val2 && stop_time < new Date())){  // スケジュールの日付が現在より過去のとき または 当日で受付時間を過ぎている場合
              array.push(2);
            }else{
              array.push(0);     // 0: 未入力, 1: 出席, 2: 欠席(連絡なし), 3: 欠席(連絡あり), 4: 遅刻, 5: 実験なし
            }
          }else{
            array.push(5)
          }
        });
        this.dataTable.push(array);
      });
    },
    setDataTable: function(start_time, stop_time){
      // 出席テーブルに設定(出席、遅刻)
      return new Promise((resolve01, reject) => {
        this.logs.forEach((val1, index1) => {
          this.userData.forEach((val2, index2) => {
            if(val1.number === val2.number){
              this.dateArray.forEach((val3, index3) => {
                const promise02 = new Promise((resolve02, reject) => {
                  if(val1.created_at.split(' ')[0] === val3 && this.dataTable[index2][index3] !== 5){   // 出席日であれば
                    var create_date = this.setTime(val1.created_at.split(' ')[1]); // setTimeで時間比較できる形に変換している
                    if (start_time <= create_date && create_date <= stop_time) {
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
                promise02.then(() => { // 最終のとき
                  if(index1 === this.logs.length - 1 && index3 === this.dateArray.length - 1){
                    resolve01();
                  }
                });
              });
            }
          });
        });
      });
    },
    initDataList: function(){
      // 欠席者リストの初期化
      this.dateArray.forEach((val1, index1) => {
        var array1 = [];
        var array2 = [];
        this.dataTable.forEach((val2, index2) => {
          if(val2[index1] === 2){
            array1.date = val1;
            var user = Object.create(this.userData[index2]);
            array2.push({...Object.getPrototypeOf(user), ...this.createDataStruct(null), date: val1});
            // userData. [id, name, number, team, grade, index, created_at]
          }
        });
        if(array2.length > 0){
          array1.user = array2;
          this.dataList.push(array1);
        }
      });
    },
    setDataList: function(sublog){
      // 欠席者リストの設定
      this.dataTable.forEach((val1, index1) => {
        if(val1.indexOf(2) !== -1){ // 欠席がある学生の時のみ書き換えを行う
          val1.forEach((val2, index2) => {
            if(val2 === 2){
              delIndex = null;
              sublog.some((val3, index3) => {
                if(val3.number === this.userData[index1].number && val3.date === this.dateArray[index2]){
                  delIndex = index3;
                  // 出欠席表の書き換え
                  if(val3.check01 !== 0){ // 連絡がなし(0)以外, つまりメール(1)もしくは電話(2)なら連絡ありと判定
                    val1[index2] = 3; // val2 = 3だと反映されない
                  }
                  // 欠席者リストの書き換え
                  this.dataList.forEach((val4, index4) => { 
                    if(val4.date === val3.date){
                      val4.user.forEach((val5, index5) => {
                        if(val5.number === val3.number){
                          val4.user[index5] = {...this.userData[index1], ...this.createDataStruct(val3), date: val3.date}
                        }
                      });
                    }
                  });
                  return true; // sublogのループから抜ける (処理の軽量化)
                };
              });
              if(delIndex !== null){
                sublog.splice(delIndex, 1); // 確認したsublogを削除(処理の軽量化)
              }
            }
          });
        }
      });
      this.fields.loadFlag = true;
    },
    createDataStruct: function(val){
      // 欠席者リスト用の変数を作成
      return {
        lateRemarks: (val === null) ? null : val.remarks,
        newRemarks: (val === null) ? null : val.remarks,
        lateCheck01: (val === null) ? '0' : val.check01,
        newCheck01: (val === null) ? '0' : val.check01,
        lateCheck02: (val === null) ? '0' : val.check02,
        newCheck02: (val === null) ? '0' : val.check02,
        lateCheck03: (val === null) ? '0' : val.check03,
        newCheck03: (val === null) ? '0' : val.check03,
        lateCheck04: (val === null) ? '0' : val.check04,
        newCheck04: (val === null) ? '0' : val.check04,
        lateDate01: (val === null) ? '' : val.date01,
        newDate01: (val === null) ? '' : val.date01,
        lateDate02: (val === null) ? '' : val.date02,
        newDate02: (val === null) ? '' : val.date02,
        lateCheck05: (val === null) ? '0' : val.check05,
        newCheck05: (val === null) ? '0' : val.check05,
      }
    },
    storeData: function(){
      var createData = [];
      var updateData = [];
      this.dataList.forEach((val1, index1) => {
        val1.user.forEach((val2, index2) => {
          new Promise((resolve03, reject) => {
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
          }).then(() => {
            if(index1 === this.dataList.length - 1 && index2 === val1.user.length - 1){
              new Promise((resolve04, reject) => {
                if(createData.length > 0){
                  console.log(createData);
                  axios.post(`http://localhost:${port}/backend/dbc5.php`,{
                    func: 'create',
                    data: createData
                  }).then((res) => {
                    console.log('作成完了');
                    resolve04();
                  });
                }else{
                  resolve04();
                }
              }).then(() => {
                if(updateData.length > 0){
                  axios.post(`http://localhost:${port}/backend/dbc5.php`,{
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
      // 現在の日付を取得
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
      // 時間比較できる形にフォーマットを変える
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
    changeMode: function(){
      sessionStorage.setItem('logSelectMode', this.fields.selectMode);
      // this.getUserData();
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
  }
}
</script>

      