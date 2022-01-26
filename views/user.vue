<template>
  <main>
    <modal-component ref="modalChild" @data-update="getData" @dataflag-update="dataFlagUpdate"></modal-component>

    <main-header-component :grade="fields.grade" :linkname="fields.linkName" :dataflag="fields.dataFlag" @open-modal="openModal"></main-header-component>

    <div class="main-contena">
      <div class="form-contena user">
        <div class="left-contena">
          <p class="amount-text">すべて：
            <span v-if="users.mode1.front.length > 0 && users.mode1.back.length > 0 && fields.selectMode === '1'">{{ users.mode1.front.length + users.mode1.back.length }}</span>
            <span v-else-if="users.mode2.length > 0 && fields.selectMode === '2'">{{ users.mode2.length }}</span>
          </p>
          <div class="select-contena">
            <select id="select" v-model="fields.selectMode" @change="changeMode">
              <option value="1">名簿順</option>
              <option value="2" v-if="fields.grade !== '1EC'">班順</option>
            </select>
          </div>
        </div>
        <div class="right-contena">
          <input type="text" class="search-text">
        </div>
      </div>
      <div v-if="fields.dataFlag">
        <table class="user">
          <thead>
            <tr>
              <th class="index-column"></th>
              <th class="number-column">学籍番号</th>
              <th class="name-column">名前</th>
              <th v-if="fields.grade !== '1EC'">班</th>
            </tr>
          </thead>
          <!-- 学籍番号順 -->
          <tbody v-if="fields.selectMode == '1'"> 
            <tr v-for="(user, index) in users.mode1.front" v-bind:key="user.id">
              <td>{{ index + 1 }}</td>
              <td>{{ user.number }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.team }}</td>
            </tr>
            <tr class="dammy-row"><td></td><td></td><td></td></tr>
            <tr v-for="(user, index) in users.mode1.back" v-bind:key="user.id">
              <td>{{ index + 1 }}</td>
              <td>{{ user.number }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.team }}</td>
            </tr>
          </tbody>
          <!-- 班順 -->
          <tbody v-else>
            <tr v-for="(user, index) in users.mode2" v-bind:key="user.id">
              <td>{{ index + 1 }}</td>
              <td>{{ user.number }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.team }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="no-data" v-else>
        <div class="icon-contena">
          <i class="fas fa-users"></i>
        </div>
        <p>学生データがありません。</p>
        <p>学生データを登録して、一括管理しましょう。</p>
      </div>
    </div>

  </main>
</template>

<script>
module.exports = {
  initData: function(){
    return {
      users: {
        mode1: {
          front: [],
          back: []
        },
        mode2: [],
        postData: []
      },
      fields: {
        grade: '',
        linkName: '',
        selectMode: '1',
        dataFlag: false,
        showFlag: false,
        hideFlag: false,
      },
    };
  },
  data: function() {
    return this.$options.initData();
  },
  mounted: function(){
    this.fields.grade = sessionStorage.getItem('grade');
    this.fields.linkName = sessionStorage.getItem('linkName');
    if(sessionStorage.getItem('selectMode')){
      this.fields.selectMode = sessionStorage.getItem('selectMode');
    }
    this.getData();
  },
  methods: {
    resetData: function(){
      Object.assign(this.$data, this.$options.initData());
    },
    getData: function(){
      axios.get(`http://localhost:${port}/dbc.php`,{
        params: {
          mode: this.fields.selectMode,
          grade: this.fields.grade
        }
      }).then((res) => {
        if(res.data.param.length > 0){
          if(this.fields.selectMode == '1'){
            this.users.mode1.front = this.sortData(res.data.param)[0]
            this.users.mode1.back = this.sortData(res.data.param)[1]
          }else{
            this.users.mode2 = [];
            var array = res.data.param;
            array.forEach((val1, index1) => {
              this.users.mode2.push(...this.sortData(val1.user)[0].concat(this.sortData(val1.user)[1]));
              // concatは配列の連結 array1.concat(array2)
            });
          }
          this.fields.dataFlag = true;
        }
      });
    },
    sortData: function(data){
      grade_type = this.fields.grade.substr(1,2) // 2NCならNCを取り出す
      num_front = ''; // 学籍番号の上２桁
      array = [] // [[16,0,1],[17,2,3],[18,4,32],[19,33,34]]
      back_array = []
      data.forEach((val, index) => {
        if(grade_type !== val.number.substr(2,2)){ // 昼と夜が違う学生はback_arrayに先に入れる
          back_array.push(val)
          return
        }
        if(num_front !== val.number.substr(0, 2)){
          num_front = val.number.substr(0, 2);
          array.push([num_front, index, null])
        }
        array[array.length - 1][2] = index
      });
      // 最も人数の多い学年がどれかを決定する
      main_index = 0 // 人数が最も多い学年のインデックス(今回は18生なので2となる)
      max_amount = 0 
      array.forEach((val, index) => {
        if(val[2] - val[1] >= max_amount){
          main_index = index
          max_amount = val[2] - val[1]
        }
      });
      // frontには最も人数の多い学年を昇順, backはそれ以外の学年を昇順
      front_array =  data.slice(array[main_index][1], array[main_index][2] + 1);
      back_array_temp = []
      array.forEach((val, index) => {
        if(index !== main_index){
          back_array_temp.push(...data.slice(val[1], val[2] + 1))
        }
      });
      back_array.unshift(...back_array_temp)
      return [
        front_array,
        back_array
      ]
    },
    changeMode: function(){
      sessionStorage.setItem('selectMode', this.fields.selectMode);
      this.getData();
    },
    openModal: function(func){
      this.$refs.modalChild.openModal(this.fields.grade, this.fields.linkName, func);
    },
    dataFlagUpdate: function(){
      this.fields.dataFlag = false;
      this.users.mode1.front = []
      this.users.mode1.back = []
      this.users.mode2 = []
    }
  }
}
// https://vue.hatenablog.com/entry/2019/02/19/211652　　　　Excel参考
</script>