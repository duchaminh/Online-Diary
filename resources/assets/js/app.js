
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
  require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example', require('./components/Example.vue'));

const app = new Vue({
    el: '#app',
    data:{
      msg:"testttttttttttt",
      commentData:"",
      comments:[],
      
    },
    methods:{
    	addComment(id){
         
	       axios.post('http://localhost:8000/writeComment', {
	       	id_diary:id,
	       	comment:this.commentData
          })
          .then(function (response) {
            console.log('saved successfully'); // show if success
            if(response.status===200){
              app.comments = response.data;
            }
          })
          .catch(function (error) {
            console.log(error); // run if we have error
          });

      },
     
    }
    	 
});
