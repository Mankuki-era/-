<template>
  <main>
    <modal-component ref="modalChild" @data-update="getData" @dataflag-update="dataFlagUpdate"></modal-component>

    <main-header-component :grade="fields.grade" :linkname="fields.linkName" :dataflag="fields.connectFlag" @open-modal="openModal" @connect="connectClick"></main-header-component>

    <div class="main-contena">
      <loading-bar-component ref="loadBarChild"></loading-bar-component>
      <div class="form-contena main">
        <p class="status-text">ステータス：<span v-if="fields.connectFlag">接続中</span><span v-else>未接続</span></p>
      </div>
      <div v-if="fields.connectFlag">
        <table class="main">
          <thead>
            <tr>
              <th class="number-column">学籍番号</th>
              <th class="name-column">入室時間</th>
              <th class="name-column">体温</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user, index) in users" v-bind:key="index">
              <td>{{ user.number }}</td>
              <td>{{ user.created_at }}</td>
              <td>{{ user.temperature }}℃</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="no-data" v-else>
        <div class="icon-contena">
          <i class="fas fa-link"></i>
        </div>
        <p>接続していません。</p>
        <p>RaspberryPiと接続して、出席状況を監視しましょう。</p>
      </div>
    </div>

  </main>
</template>

<script>
module.exports = {
  initData: function(){
    return {
      users: {},
      fields: {
        grade: '',
        linkName: '',
        connectFlag: false,
        connectTime: '',
        timer: null,
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
    if(sessionStorage.getItem('connectTime')){
      this.fields.connectTime = sessionStorage.getItem('connectTime');
    }
    if(sessionStorage.getItem('connectFlag')){
      this.fields.connectFlag = JSON.parse(sessionStorage.getItem('connectFlag').toLowerCase());
    }
    this.pollingAction();
  },
  methods: {
    resetData: function(){
      Object.assign(this.$data, this.$options.initData());
    },
    getDate: function(){
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
      var hour = toDoubleDigits(date.getHours());
      var minute = toDoubleDigits(date.getMinutes());
      var second = toDoubleDigits(date.getSeconds());
      return String(year) + "-" + String(month) + "-" + String(day) + " " + String(hour) + ":" + String(minute) + ":" + String(second);
    },
    getData: function(){
      axios.get("http://localhost:81/dbc3.php", {
        params: {
          time: this.fields.connectTime
        }
      }).then((res) => {
        this.users = res.data.param;
      });
    },
    connectClick: function(bool){
      this.$refs.loadBarChild.loadEvent(true);
      sessionStorage.setItem('connectFlag', bool);
      setTimeout(() => {
        this.fields.connectFlag = bool;
        sessionStorage.setItem('connectTime', this.getDate());
        this.fields.connectTime = this.getDate();
        this.$refs.loadBarChild.loadEvent(false);
        this.pollingAction();
      }, 3000);
    },
    pollingAction: function(){
      if(this.fields.connectFlag){
        this.fields.timer = setInterval(() => {
          this.getData();
        }, 5000);
      }else{
        clearInterval(this.fields.timer);
      }
    },
    openModal: function(func){
      this.$refs.modalChild.openModal(this.fields.grade, this.fields.linkName, func);
    },
    dataFlagUpdate: function(){
      this.fields.dataFlag = false;
    }
  },
  destroyed: function () {
    clearInterval(this.fields.timer)
  }
}
</script>