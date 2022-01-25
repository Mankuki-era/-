<template>
  <div class="header-contena">
    <div class="pankuzu-contena">
      <p>{{ grade }} > {{ linkname }}</p>
    </div>
    <div class="header-box">
      <h1>{{ linkname }}</h1>
      <div class="button-contena">
        <ul class="button-box" v-if="linkname === '学生情報管理'">
          <li class="menu-first link-button second small change-width-small">
            <a href="#" @click.prevent.stop="clickDropDown">その他</a>
            <ul class="menu-second" :class="{show: showFlag, hide: hideFlag}">
              <li><a href="">ダウンロード</a></li>
            </ul>
          </li>
          <li class="link-button second small" :class="{disabled: !dataflag}"><a href="" @click.prevent.stop="openModal('delete')">データ削除</a></li>
          <li class="link-button first small"><a href="" @click.prevent.stop="openModal('create')">データ追加</a></li>
        </ul>
        <ul class="button-box" v-else-if="linkname === 'スケジュール管理'">
          <li class="menu-first link-button second small change-width-small">
            <a href="#" @click.prevent.stop="clickDropDown">その他</a>
            <ul class="menu-second" :class="{show: showFlag, hide: hideFlag}">
              <li><a href="">ダウンロード</a></li>
            </ul>
          </li>
          <li class="link-button second small" :class="{disabled: !dataflag}"><a href="" @click.prevent.stop="openModal('delete')">データ削除</a></li>
          <li class="link-button second small" :class="{disabled: !dataflag}"><a href="" @click.prevent.stop="openModal('update')">データ編集</a></li>
          <li class="link-button first small" :class="{disabled: dataflag}"><a href="" @click.prevent.stop="openModal('create')">データ追加</a></li>
        </ul>
        <ul class="button-box" v-else-if="linkname === 'ホーム'">
          <li class="link-button first small"  :class="{disabled: dataflag}"><a href="" @click.prevent.stop="connectAction(true)">接続開始</a></li>
          <li class="link-button first small"  :class="{disabled: !dataflag}"><a href="" @click.prevent.stop="connectAction(false)">接続終了</a></li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
module.exports = {
  props: ['grade', 'linkname', 'dataflag'],
  data: function(){
    return {
      showFlag: false,
      hideFlag: false
    }
  },
  mounted: function(){
    window.addEventListener('click', this.closeDropDown);
  },
  methods: {
    openModal: function(func){
      this.$emit('open-modal', func);
    },
    clickDropDown: function(){
      if(this.showFlag){
        this.showFlag = false;
        this.hideFlag = true;
        setTimeout(() => {
          this.hideFlag = false;
        }, 200);
      }else{
        this.showFlag = true;
      }
    },
    closeDropDown(event) {
      if(this.showFlag){
        if (!this.$el.querySelector('.menu-first').contains(event.target)){
          this.showFlag = false;
          this.hideFlag = true;
          setTimeout(() => {
            this.hideFlag = false;
          }, 500);
        }
      }
    },
    connectAction: function(bool){
      this.$emit('connect', bool);
    }
  }
}
</script>