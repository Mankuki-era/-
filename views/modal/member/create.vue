<template>
  <div class="modal-contents-member-create">
    <div class="sample-area">
      <p>サンプルファイルをダウンロードし、ファイル内のフォーマットに沿ってデータを追加してください.</p>
      <li class="sample-download"><a href="../../doc/format.xlsx" download><img src="../img/excel.png" alt="画像">XLSXサンプルをダウンロード</a></li>
    </div>
    <div class="preview-area">
      <p class="msg err-msg" v-show="fields.fileErr">{{ fields.errAmount }}箇所でエラーが発生しました.</p>
      <p class="msg success-msg" v-show="!fields.fileErr && users.preview.length > 0">データは正常です.</p>
      <div class="preview-box">
        <p class="no-preview" v-if="users.preview.length === 0">プレビュー表示エリア</p>
        <table v-else>
          <thead>
            <tr>
              <th></th>
              <th>学籍番号</th>
              <th>名前</th>
              <th v-if="grade !== '1EC'">班</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user, index) in users.preview" v-bind:key="index">
              <td>{{ index + 1 }}</td>
              <td :class="{err: user.number.err !== ''}">{{ user.number.val }}</td>
              <td :class="{err: user.name.err !== ''}">{{ user.name.val }}</td>
              <td v-if="grade !== '1EC'" :class="{err: user.team.err !== ''}">{{ user.team.val }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="file-area">
      <label><input type="file" id="file" @change="handleFile">ファイル選択</label><span>{{ fields.fileName }}</span>
    </div>
    <ul>
      <li class="link-button second middle"><a href="" @click.prevent.stop="closeModal">キャンセル</a></li>
      <li class="link-button first middle" :class="{disabled: users.preview.length == 0 || fields.fileErr}"><a href="" @click.prevent.stop="createData">追加</a></li>
    </ul>
  </div>
</template>

<script>
module.exports = {
  props: ['grade'],
  initData: function(){
    return {
      users: {
        preview: [],
        postData: []
      },
      fields: {
        fileName: '選択されていません',
        fileErr: false,
        errAmount: 0
      },
    };
  },
  data: function() {
    return this.$options.initData();
  },
  methods: {
    resetData: function(){
      Object.assign(this.$data, this.$options.initData());
    },
    fixdata: function(resultData) {
      let o = "",
          l = 0,
          w = 10240;
      for (; l < resultData.byteLength / w; ++l) o += String.fromCharCode.apply(null, new Uint8Array(resultData.slice(l * w,
          l * w + w)));
      o += String.fromCharCode.apply(null, new Uint8Array(resultData.slice(l * w)));
      return o;
    },
    to_json: function(workbook, X) {
      let result = {};
      workbook.SheetNames.forEach(function (sheetName) {
        const roa = X.utils.sheet_to_json(
          workbook.Sheets[sheetName],
          {
              raw: true,
          });
        if (roa.length > 0) {
          result[sheetName] = roa;
        }
      });
      return result;
    },
    handleFile: function(e) {
      this.users.preview = [];
      this.users.postData = [];
      const X = XLSX;
      const files = e.target.files;
      const f = files[0];
      this.fields.fileName = f.name;
      const reader = new FileReader();
      const loadReader = (e) => {
        const resultData = e.target.result;
        const arr = this.fixdata(resultData);
        const wb = X.read(btoa(arr), {
          type: 'base64',
          cellDates: true,
        });
        const output = this.to_json(wb, X);
        for (let i of output['Sheet1']) {
          var obj = this.checkData(i.number, i.name, i.team);
          var err_num = obj.err_num;
          var err_name = obj.err_name;
          var err_team = obj.err_team;
          var array = {
            number: {
              val: i.number,
              err: err_num
            },
            name: {
              val: i.name,
              err: err_name
            },
            team: {
              val: i.team,
              err: err_team
            }
          };
          this.users.preview.push(array);
          this.users.postData.push(i);
        }
      };
      reader.readAsArrayBuffer(f);
      reader.onload = loadReader;
      document.getElementById("file").value = '';
    },
    checkData: function(number, name, team){
      var err_num = '';
      var err_name = '';
      var err_team = '';
      if(number === undefined){
        err_num = '入力値がありません';
        this.fields.fileErr = true;
        this.fields.errAmount += 1;
      };
      if(name === undefined){
        err_name = '入力値がありません';
        this.fields.fileErr = true;
        this.fields.errAmount += 1;
      };
      if(this.grade !== '1EC'){ // 1ECは班がないためエラーと判定しない
        if(team === undefined){
          err_team = '入力値がありません';
          this.fields.fileErr = true;
          this.fields.errAmount += 1;
        };
      }
      return {
        err_num: err_num,
        err_name: err_name,
        err_team: err_team
      };
    },
    closeModal: function(){
      this.$emit('close-modal');
    },
    createData: function(){
      axios.post("http://localhost:8888/dbc.php",{
        func: 'create',
        grade: this.grade,
        users: this.users.postData
      }).then((res) => {
        this.$emit('create-comp');
      });
    },
  }
}
</script>