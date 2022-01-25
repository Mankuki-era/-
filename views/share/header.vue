<template>
  <header>
    <div class="grade-box" v-if="fields.grade !== ''">{{ fields.grade }}</div>
  </header>
</template>

<script>
module.exports = {
  initData: function(){
    return {
      fields: {
        grade: ''
      },
    }
  },
  data: function() {
    return this.$options.initData();
  },
  mounted: function(){
    this.setGrade();
  },
  methods: {
    resetData: function(){
      Object.assign(this.$data, this.$options.initData());
    },
    setGrade: function(){
      if(sessionStorage.getItem('grade')){
        this.fields.grade = sessionStorage.getItem('grade');
      }
    },
  }
}
</script>