<template>
  <div class="bar-box">
    <div class="bar01" :class="{animation: animationFlag}"></div>
    <div class="bar02" :class="{animation: animationFlag}"></div>
    <div class="bar03" :class="{animation: animationFlag}"></div>
    <div class="bar04" :class="{animation: animationFlag}"></div>
  </div>
</template>

<script>
module.exports = {
  initData: function(){
    return {
      animationFlag: false
    }
  },
  data: function() {
    return this.$options.initData();
  },
  methods: {
    loadEvent: function(bool){
      if(bool){
        this.animationFlag = true;
      }else{
        this.animationFlag = false;
      }
    }
  }
}
</script>