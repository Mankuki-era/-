const router = new VueRouter({
  // mode:'history',
  routes: [
      { 
        path: '/',
        component: httpVueLoader('../views/home.vue'),
      },
      { 
        path: '/main',
        component: httpVueLoader('../views/main.vue'),
        beforeEnter: (to, from, next) => {
          if(sessionStorage.getItem('grade')){
            next();
          }else{
            next({path: '/'});
          }
        }
      },
      { 
        path: '/user',
        component: httpVueLoader('../views/user.vue'),
        beforeEnter: (to, from, next) => {
          if(sessionStorage.getItem('grade')){
            next();
          }else{
            next({path: '/'});
          }
        }
      },
      {
        path: '/schedule',
        component: httpVueLoader('../views/schedule.vue'),
        beforeEnter: (to, from, next) => {
          if(sessionStorage.getItem('grade')){
            next();
          }else{
            next({path: '/'});
          }
        }
      },
      {
        path: '/page3',
        component: httpVueLoader('../views/page3.vue'),
        beforeEnter: (to, from, next) => {
          if(sessionStorage.getItem('grade')){
            next();
          }else{
            next({path: '/'});
          }
        }
      },
  ]
});

// コンポーネントをグローバル登録
Vue.component('header-component', httpVueLoader('../views/share/header.vue'));
Vue.component('loading-bar-component', httpVueLoader('../views/share/loading-bar.vue'));
Vue.component('nav-component', httpVueLoader('../views/share/nav.vue'));
Vue.component('modal-component', httpVueLoader('../views/share/main/modal.vue'));
Vue.component('main-header-component', httpVueLoader('../views/share/main/header.vue'));
Vue.component('alert-component', httpVueLoader('../views/modal/alert.vue'));
Vue.component('member-create-component', httpVueLoader('../views/modal/member/create.vue'));
Vue.component('schedule-create-update-component', httpVueLoader('../views/modal/schedule/create-update.vue'));

const app = new Vue({
  el: "#app",
  router,
  methods: {
    loadBarEvent: function(bool){
      this.$refs.loadBarChild.loadEvent(bool);
    },
    gradeShow: function(){
      this.$refs.headerChild.setGrade();
    }
  }
});