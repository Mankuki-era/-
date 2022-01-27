<template>
  <nav v-if="this.$route.path !== '/'">
    <div class="link-contena">
      <ul>
        <li v-for="(link, index) in linkArray" :key="index">
          <a :class="{selected: link.flag}" @click.prevent.stop="clickAction(index)"><div v-html="link.icon"></div>{{ link.name }}</a>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
module.exports = {
  initData: function(){
    return {
      linkArray: [
        {path: '/main', name: 'ホーム', flag: false, icon: '<i class="fas fa-home"></i>'},
        {path: '/user', name: '学生情報管理', flag: false, icon: '<i class="fas fa-users"></i>'},
        {path: '/schedule', name: 'スケジュール管理', flag: false, icon: '<i class="fas fa-calendar-alt"></i>'},
        {path: '/log', name: '出席管理', flag: false, icon: '<i class="fas fa-database"></i>'},
        {path: '/', name: 'ログアウト', flag: false, icon: '<i class="fas fa-sign-out-alt"></i>'},
      ]
    }
  },
  data: function() {
    return this.$options.initData();
  },
  mounted: function(){
    this.setLinkName();
  },
  methods: {
    resetData: function(){
      Object.assign(this.$data, this.$options.initData());
    },
    setLinkName: function(){
      if(sessionStorage.getItem('linkName') === null){
        this.resetData();
        this.linkArray[0].flag = true;
        sessionStorage.setItem('linkName', this.linkArray[0].name);
      }else{
        this.linkArray.forEach(val => {
          if(val.name === sessionStorage.getItem('linkName')){
            val.flag = true;
          }
        });
      }
    },
    clickAction: function(index){
      sessionStorage.setItem('linkName', this.linkArray[index].name);
      this.resetData();
      this.linkArray[index].flag = true;
      this.$emit('load-bar-event', true);
      setTimeout(() => {
        this.$router.push({
          path: this.linkArray[index].path
        });
        if(this.linkArray[index].name === 'ログアウト'){
          sessionStorage.clear();
          this.setLinkName();
        }
        this.$emit('load-bar-event', false);
      }, 1000);
    },
  }
}
</script>