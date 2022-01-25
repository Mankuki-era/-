<template>
  <main class="root">
    <h1>学年一覧</h1>
    <div class="card-contena">
      <div v-for="(grade, index) in gradeArray" :key="index" class="card-box">
        <div class="card-top" :class="colorMethod(index)">
          <li><a href="" @click.prevent.stop="selectAction(grade)">{{ grade }}</a></li>
        </div>
        <div class="card-bottom">
          <p>実施日:木曜 6,7限</p>
          <p class="date">最新：2021/11/30</p>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
module.exports = {
  initData: function(){
    return {
      gradeArray: ['1EC', '2EC', '2NC', '3EC', '3NC', '4EC', '4NC']
    }
  },
  data: function() {
    return this.$options.initData();
  },
  mounted: function(){
    this.$emit('grade-show');
  },
  methods: {
    selectAction: function(grade){
      sessionStorage.setItem('grade', grade);
      this.$emit('grade-show');
      router.push({ path: '/main' });
    },
    colorMethod: function(index){
      switch(index){
        case 0: return "color-one";
        case 1: return "color-two";
        case 2: return "color-three";
        case 3: return "color-four";
        case 4: return "color-five";
        case 5: return "color-six";
        case 6: return "color-seven";
      }
    }
  }
}
</script>