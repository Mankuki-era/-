<template>
  <section class="modal-area" :class="{show: fields.showFlag, hide: fields.hideFlag}">
    <div class="modal-mask" @click="closeModal"></div>
    <div class="modal-contena" :class="{small: (fields.modalMode === 'member-delete' || fields.modalMode === 'schedule-delete')}">
      <div class="modal-box">
        <h1 :class="{small: fields.modalMode === 'delete'}">{{ fields.modalTitle }}</h1>
        <member-create-component v-if="fields.modalMode === 'member-create'" :grade="fields.grade" @close-modal="closeModal" @create-comp="createComp"></member-create-component>
        <schedule-create-update-component v-else-if="fields.modalMode === 'schedule-create' || fields.modalMode === 'schedule-update'" :grade="fields.grade" :mode="fields.modalMode" @close-modal="closeModal" @create-comp="createComp"></schedule-create-update-component>
        <alert-component v-else-if="fields.modalMode === 'member-delete' || fields.modalMode === 'schedule-delete'" :grade="fields.grade" :mode="fields.modalMode" @close-modal="closeModal" @delete-comp="deleteComp"></alert-component>
      </div>
      <div class="close-modal" @click="closeModal">×</div>
    </div>
  </section>
</template>

<script>
module.exports = {
  initData: function(){
    return {
      fields: {
        grade: '',
        showFlag: false,
        hideFlag: false,
        modalTitle: '',
        modalMode: ''
      }
    };
  },
  data: function() {
    return this.$options.initData();
  },
  methods: {
    openModal: function(grade, linkName, func){
      this.setData(grade, linkName, func);
      this.fields.hideFlag = false;
      this.fields.showFlag = true;
    },
    closeModal: function(){
      this.fields.showFlag = false;
      this.fields.hideFlag = true;
      setTimeout(() => {
        this.fields.hideFlag = false;
        this.resetData();
      }, 300);
    },
    resetData: function(){
      Object.assign(this.$data, this.$options.initData());
    },
    setData: function(grade, linkName, func){
      this.fields.grade = grade;
      if(func === 'delete'){
        this.fields.modalTitle = 'データを削除しますか？';
      }
      if(linkName === '学生情報管理'){
        if(func === 'create'){
          this.fields.modalTitle = 'データ一括追加';
        }
        this.fields.modalMode = 'member-' + func;
      }else if(linkName === 'スケジュール管理'){
        if(func === 'create'){
          this.fields.modalTitle = 'スケジュール作成';
        }else if(func === 'update'){
          this.fields.modalTitle = 'スケジュール作成';
        }
        this.fields.modalMode = 'schedule-' + func;
      };
    },
    createComp: function(){
      this.closeModal();
      this.$emit('data-update');
    },
    deleteComp: function(){
      this.closeModal();
      this.$emit('dataflag-update');
    }
  }
}
</script>