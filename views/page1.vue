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
          <tbody v-if="fields.selectMode == '1'">
            <tr v-for="(user, index) in users.mode1.back" v-bind:key="user.id">
              <td>{{ index + 1 }}</td>
              <td>{{ user.number }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.team }}</td>
            </tr>
            <tr class="dammy-row"><td></td><td></td><td></td></tr>
            <tr v-for="(user, index) in users.mode1.front" v-bind:key="user.id">
              <td>{{ index + 1 }}</td>
              <td>{{ user.number }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.team }}</td>
            </tr>
          </tbody>
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
      axios.get("http://localhost:8888/dbc.php",{
        params: {
          mode: this.fields.selectMode,
          grade: this.fields.grade
        }
      }).then((res) => {
        if(res.data.param.length > 0){
          if(this.fields.selectMode == '1'){
            num_front = '';
            num_front_index = 0;
            res.data.param.forEach((val, index) => {
              if(num_front !== val.number.substr(0, 2)){
                num_front = val.number.substr(0, 2);
                num_front_index = index;
              }
            });
            this.users.mode1.front = res.data.param.slice(0, num_front_index);
            this.users.mode1.back = res.data.param.slice(num_front_index);
          }else{
            this.users.mode2 = [];
            var array = res.data.param;
            array.forEach((val1, index1) => {
              num_front = '';
              num_front_index = 0;
              val1.user.forEach((val2, index2) => {
                if(num_front !== val2.number.substr(0, 2)){
                  num_front = val2.number.substr(0, 2);
                  num_front_index = index2;
                }
              });
              // val1.user = val1.user.slice(num_front_index).concat(val1.user.slice(0, num_front_index));
              this.users.mode2.push(...val1.user.slice(num_front_index).concat(val1.user.slice(0, num_front_index)));
            });
            // this.users.mode2 = array;
          }
          this.fields.dataFlag = true;
        }
      });
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
    }
  }
}
// https://vue.hatenablog.com/entry/2019/02/19/211652　　　　Excel参考
</script>