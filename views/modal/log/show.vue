<template>
  <div class="modal-contents-log">
    <div class="log-card-header">
      <div class="select-contena" :class="{}">
        <select v-model="status" @change="changeStatus">
          <option v-for="(value, index) in statusArray" :key="index">{{ value }}</option>
        </select>
      </div>
    </div>
    <div class="log-card-main">
      <div class="left-box">
        <p>学籍番号<span>:</span></p>
        <p>氏名<span>:</span></p>
        <p>日付<span>:</span></p>
      </div>
      <div class="right-box">
        <p>{{ number }}</p>
        <p>{{ name }}</p>
        <p>{{ date }}</p>
      </div>
    </div>
    <ul>
      <li class="link-button second middle"><a href="" @click.prevent.stop="closeModal">戻る</a></li>
      <li class="link-button first middle" :class="{disabled: !fields.usableFlag}"><a href="" @click.prevent.stop="updateData">変更</a></li>
    </ul>
  </div>
</template>

<script>
module.exports = {
  props: ['data'],
  data: function(){
    return {
      fields: {
        usableFlag: false,
      },
      originStatus: '',
      status: '',
      statusArray: ['出席', '欠席(連絡なし)', '欠席(連絡あり)', '遅刻'],
      name: this.data[0].name,
      number: this.data[0].number,
      date: this.data[1]
    }
  },
  mounted: function(){
    this.setData();
  },
  methods: {
    closeModal: function(){
      this.$emit('close-modal');
    },
    resetData: function(){
      Object.assign(this.$data, this.$options.initData());
    },
    setData: function(){
      // 0: 未入力, 1: 出席, 2: 欠席(連絡なし), 3: 欠席(連絡あり), 4: 遅刻, 5: 実験なし
      if(this.data[2] === 1){
        this.originStatus = '出席';
      }else if(this.data[2] === 2){
        this.originStatus = '欠席(連絡なし)';
      }else if(this.data[2] === 3){
        this.originStatus = '欠席(連絡あり)';
      }else if(this.data[2] === 4){
        this.originStatus = '遅刻';
      }
      this.status = this.originStatus;
    },
    changeStatus: function(){
      if(this.status !== this.originStatus){
        this.fields.usableFlag = true;
      }else{
        this.fields.usableFlag = false;
      }
    },  
    updateData: function(){

    }
  }
}
</script>