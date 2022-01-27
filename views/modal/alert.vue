<template>
  <div class="modal-contents-member-delete">
    <p class="msg">データは物理削除されます.</p>
    <ul>
      <li class="link-button second middle"><a href="" @click.prevent.stop="closeModal">キャンセル</a></li>
      <li class="link-button first middle"><a href="" @click.prevent.stop="okAction">OK</a></li>
    </ul>
  </div>
</template>

<script>
module.exports = {
  props: ['mode', 'grade'],
  methods: {
    closeModal: function(){
      this.$emit('close-modal');
    },
    resetData: function(){
      Object.assign(this.$data, this.$options.initData());
    },
    okAction: function(){
      if(this.mode === 'member-delete'){
        this.deleteMemberData();
      }else if(this.mode === 'schedule-delete'){
        this.deleteScheduleData();
      }
    },
    deleteMemberData: function(){
      axios.post(`http://localhost:${port}/backend/dbc1.php`, {
        func: 'delete',
        grade: this.grade
      }).then((res) => {
        this.$emit('delete-comp');
      })
    },
    deleteScheduleData: function(){
      axios.post(`http://localhost:${port}/backend/dbc2.php`,{
        func: 'delete',
        grade: this.grade
      }).then((res) => {
        this.$emit('delete-comp');
      });
    }
  }
}
</script>