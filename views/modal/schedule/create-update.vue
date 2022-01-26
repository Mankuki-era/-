<template>
  <div class="modal-contents-schedule-create">
    <div class="menu">

      <label class="main-label">
        <span class="step-number">1</span>実験日を選択
        <span class="success" v-if="fields.firstStep.flag">入力済み</span>
        <span class="error" v-else>{{ fields.firstStep.err }}</span>
      </label>
      <input type="checkbox" id="menu_bar01" checked />
      <div class="first-contena">
        <p class="msg">実験が行われるすべての日付を入力してください.</p>
        <div class="form-contena">
          <div class="left-contena">
            <div class="form-box">
              <p>第１回実験日</p>
              <label class="date-edit"><input type="date" value="2019-08-22" v-model="fields.startDate" @change="changeStartDate"></label>
            </div>
            <div class="form-box">
              <p>実験実施回数</p>
              <div class="select-contena">
                <select id="select" v-model="fields.dateAmount" @change="changeDateAmount">
                  <option v-for="(number, index) in numberArray" :key="index">{{ number }}</option>
                </select>
              </div>
            </div>
          </div>
          <div class="right-contena">
            <p>実験日一覧</p>
            <div class="preview-area">
              <div class="date-row" v-for="(date, index) in dateArray" :key="index">
                <p class="count">第{{ index + 1 }}回</p>
                <label class="date-edit first" v-if="index == 0"><input type="date" :value="date" disabled></label>
                <label class="date-edit" v-else><input type="date" v-model="dateArray[index]"></label>
                <li class="auto-fill">
                  <a href="" v-if="date !== '' && dateArray.length > 1" @click.prevent.stop="autoCreateDate(index)"><i class="fas fa-arrow-alt-circle-down autofill-icon"></i></a>
                </li>
              </div>
            </div>
          </div>  
        </div>
        <ul>
          <li class="link-button second middle"><a href="" @click.prevent.stop="closeModal">キャンセル</a></li>
          <li class="link-button first middle" :class="{disabled: dateArray.length === 1}"><a href="" @click.prevent.stop="nextStep(2)">次へ</a></li>
        </ul>
      </div>

      <div v-if="grade !== '1EC'">
        <label class="main-label">
          <span class="step-number">2</span>実験テーマを選択
          <span class="success" v-if="fields.secondStep.flag">入力済み</span>
          <span class="error" v-else>{{ fields.secondStep.err }}</span>
        </label>
        <input type="checkbox" id="menu_bar02" />
        <div class="second-contena">
          <p class="msg">すべての実験テーマを入力してください.</p>
          <div class="form-contena">
            <div class="left-contena">
              <div class="form-box">
                <p>実験テーマ数</p>
                <div class="select-contena">
                  <select id="select" v-model="fields.themaAmount" @change="changeThemaAmount">
                    <option v-for="(number, index) in numberArray" :key="index">{{ number }}</option>
                  </select>
                </div>
              </div>
              <div class="form-box team">
                <p>班構成</p>
                <div class="team-select-box">
                  <p>
                    <span class="a">A</span>
                    <span class="c">〜</span>
                  </p>
                  <div class="select-contena">
                    <select v-model="fields.alphaIndex"  @change="changeTeam">
                      <option v-for="(team, index) in alphaArray" :key="index" :value="index">{{ team }}</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="right-contena">
              <p>実験テーマ一覧</p>
              <div class="preview-area">
                <div class="date-row" v-for="(thema, index) in themaArray" :key="index">
                  <p class="count">{{ index + 1 }}</p>
                  <input type="text" v-model="themaArray[index]">
                </div>
              </div>
            </div>
          </div>
          <ul>
            <li class="link-button second middle"><a href="" @click.prevent.stop="backStep(1)">戻る</a></li>
            <li class="link-button first middle" :class="{disabled: themaArray.length === 1}"><a href="" @click.prevent.stop="nextStep(3)">次へ</a></li>
          </ul>
        </div>

        <label class="main-label">
          <span class="step-number">3</span>実験予定を選択
          <span class="error" v-if="!fields.thirdStep.flag">{{ fields.thirdStep.err }}</span>
        </label>
        <input type="checkbox" id="menu_bar03" />
        <div class="third-contena">
          <div class="table-contena">
            <table v-if="fields.secondStep.flag">
              <thead>
                <tr>
                  <th></th>
                  <th class="thema-name" v-for="(thema, index) in themaArray" :key="index">{{ thema }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(date, index1) in dateArray" :key="index1">
                  <th>{{ date }}</th>
                  <td v-for="(thema, index2) in themaArray" :key="index2">
                    <select v-model="tableArray[index1][index2]">
                      <option></option>
                      <option :value="team" v-for="(team, index3) in teamArray" :key="index3">{{ team }}</option>
                    </select>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <ul>
            <li class="link-button second middle"><a href="" @click.prevent.stop="backStep(2)">戻る</a></li>
            <li class="link-button first middle" v-if="mode === 'schedule-create'"><a href="" @click.prevent.stop="submitAction('create')">作成</a></li>
            <li class="link-button first middle" v-else><a href="" @click.prevent.stop="submitAction('update')">更新</a></li>
          </ul>
        </div>
      </div>

      <div v-else>
        <label class="main-label">
          <span class="step-number">2</span>実験予定を選択
          <span class="error" v-if="!fields.secondStep.flag">{{ fields.secondStep.err }}</span>
        </label>
        <input type="checkbox" id="menu_bar02" />
        <div class="second-contena">
          <p class="msg">分散登校の場合は、偶数週にチェックをつけてください。</p>
          <div class="table-contena">
            <table>
              <thead>
                <tr>
                  <th class="mode">登校形態</th>
                  <th>実験日</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(date, index) in dateArray" :key="index">
                  <td class="select-contena">
                    <select v-model="dayModeArray[index]">
                      <option value="1">奇数</option>
                      <option value="2">偶数</option>
                      <option value="3">全員登校</option>
                    </select>
                  </td>
                  <td>{{ date }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <ul>
            <li class="link-button second middle"><a href="" @click.prevent.stop="backStep(1)">戻る</a></li>
            <li class="link-button first middle" v-if="mode === 'schedule-create'"><a href="" @click.prevent.stop="submitAction('create')">作成</a></li>
            <li class="link-button first middle" v-else><a href="" @click.prevent.stop="submitAction('update')">更新</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
module.exports = {
  props: ['grade', 'mode'],
  initData: function(){
    return {
      fields: {
        startDate: '',
        dateAmount: '1',
        postDateAmount: 1,
        themaAmount: 1,
        alphaIndex: 0,
        postThemaAmount: 1,
        firstStep: {flag: false, err: ''},
        secondStep: {flag: false, err: ''},
        thirdStep: {flag: false, err: ''},
      },
      dateArray: [],
      themaArray: [''],
      numberArray: [...Array(20).keys()].map(i => ++i),
      alphaArray: [...'ABCDEFGHIJKLMNOP'],
      teamArray: [],
      tableArray: [],
      dayModeArray: ['3'],
    }
  },
  data: function() {
    return this.$options.initData();
  },
  mounted: function(){
    if(this.mode === 'schedule-create'){
      this.fields.startDate = this.getDate(new Date());;
      this.dateArray = [this.fields.startDate];
    }else if(this.mode === 'schedule-update'){
      this.getData();
    }
  },
  methods: {
    getData: function(){
      axios.get(`http://localhost:${port}/dbc2.php`,{
        params: {
          grade: this.grade
        }
      }).then((res) => {
        if(res.data.param.length > 0){
          var data = JSON.parse(res.data.param[0].table_json);
          this.dateArray = data.date;
          this.themaArray = data.thema;
          this.tableArray = data.table;
          this.dayModeArray = data.daymode;
          this.fields.startDate = this.dateArray[0];
          this.fields.dateAmount = this.dateArray.length;
          this.fields.themaAmount = this.themaArray.length;
          this.fields.firstStep.flag = true;
          this.fields.secondStep.flag = true;
          this.fields.thirdStep.flag = true;
        }
      });
    },
    getDate: function(date){
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
    changeStartDate: function(){
      this.dateArray = [this.fields.startDate];
      for(var i = 1; i < this.fields.dateAmount; i++){
        this.dateArray.push('');
      };
    },
    changeDateAmount: function(){
      var amount = this.fields.dateAmount - this.fields.postDateAmount;
      if(amount >= 0){
        for(var i = 1; i <= amount; i++){
          this.dateArray.push('');
          this.dayModeArray.push('3');
        };
      }else{
        for(var i = 1; i <= -amount; i++){
          this.dateArray.pop();
          this.dayModeArray.pop();
        };
      }
      this.fields.postDateAmount = this.fields.dateAmount;
    },
    autoCreateDate: function(index){
      var date = new Date(this.dateArray[index]);
      for(var i = index + 1; i < this.dateArray.length; i++){
        date.setDate(date.getDate() + 7);
        this.dateArray.splice(i, 1, this.getDate(date));
      };
    },
    changeThemaAmount: function(){
      var amount = this.fields.themaAmount - this.fields.postThemaAmount;
      if(amount >= 0){
        for(var i = 1; i <= amount; i++){
          this.themaArray.push('');
        };
      }else{
        for(var i = 1; i <= -amount; i++){
          this.themaArray.pop();
        };
      }
      this.fields.postThemaAmount = this.fields.themaAmount;
    },
    changeTeam: function(){
      this.teamArray = this.alphaArray.slice(0, this.fields.alphaIndex + 1)
    },
    backStep: function(number){
      if(number === 1){
        document.getElementById('menu_bar01').checked = true;
        document.getElementById('menu_bar02').checked = false;
        document.getElementById('menu_bar03').checked = false;
      }else if(number === 2){
        this.tableArray = [];
        document.getElementById('menu_bar01').checked = false;
        document.getElementById('menu_bar02').checked = true;
        document.getElementById('menu_bar03').checked = false;
      }
    },
    nextStep: function(number){
      if(number === 2){
        if(this.dateArray.includes('')){
          this.fields.firstStep.flag = false;
          this.fields.firstStep.err = '入力値がありません';
        }else{
          this.fields.firstStep.flag = true;
          document.getElementById('menu_bar01').checked = false;
          document.getElementById('menu_bar02').checked = true;
          if(this.grade !== '1EC'){
            document.getElementById('menu_bar03').checked = false;
          }
        }
      }else if(number === 3){
        if(this.themaArray.includes('')){
          this.fields.secondStep.flag = false;
          this.fields.secondStep.err = '入力値がありません';
        }else{
          for(var i = 0; i < this.dateArray.length; i++){
            this.tableArray.push([]);
            for(var j = 0; j < this.themaArray.length; j++){
              this.tableArray[i].push('');
            }
          }
          this.fields.secondStep.flag = true;
          document.getElementById('menu_bar01').checked = false;
          document.getElementById('menu_bar02').checked = false;
          document.getElementById('menu_bar03').checked = true;
        }
      }
    },
    submitAction: function(func){
      if(this.grade === '1EC'){
        var schedule_json = {
          date: this.dateArray,
          daymode: this.dayModeArray
        };
        if(func === 'create'){
          this.createData(schedule_json);
        }else{
          this.updateData(schedule_json);
        }
      }else{
        var flag = false;
        this.tableArray.forEach(val => {
          var result = val.some( function(value) {
            return value !== '';
          });
          if(!flag){
            flag = result;
          }
        });
        if(flag){
          var schedule_json = {
            date: this.dateArray, // tableの行数に対応
            thema: this.themaArray, // tableの列数に対応
            table: this.tableArray // ['', 'A', 'B', '', '', 'D']のような形
          };
          if(func === 'create'){
            this.createData(schedule_json);
          }else{
            this.updateData(schedule_json);
          }
        };
      }
    },
    createData: function(schedule_json){
      axios.post(`http://localhost:${port}/dbc2.php`,{
        func: 'create',
        grade: this.grade,
        schedule_json: schedule_json
      }).then((res) => {
        this.$emit('create-comp');
      });
    },
    updateData: function(schedule_json){
      axios.post(`http://localhost:${port}/dbc2.php`,{
        func: 'update',
        grade: this.grade,
        schedule_json: schedule_json
      }).then((res) => {
        this.$emit('create-comp');
      });
    },
    closeModal: function(){
      this.$emit('close-modal');
    }
  }
}
</script>