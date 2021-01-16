
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('hideshowpassword');
// var Dropzone = require('dropzone');
var password = require('password-strength-meter');

window.Vue = require('vue');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('users-count', require('./components/UsersCount.vue').default);
Vue.component('create-jobs', require('./components/CreateJobs.vue').default);
Vue.component('job-item', require('./components/JobItem.vue').default);
Vue.component('form-status', require('./components/FormStatus.vue').default);


import vSelect from 'vue-select';
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'

Vue.component('v-select', vSelect)
Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons);

const app = new Vue({
    el: '#app',
});

$.fn.extend({
    toggleText: function(a, b){
        return this.text(this.text() == b ? a : b);
    },

    /**
     * Remove element classes with wildcard matching. Optionally add classes:
     *   $( '#foo' ).alterClass( 'foo-* bar-*', 'foobar' )
     *
     */
    alterClass: function(removals, additions) {
        var self = this;

        if(removals.indexOf('*') === -1) {
            // Use native jQuery methods if there is no wildcard matching
            self.removeClass(removals);
            return !additions ? self : self.addClass(additions);
        }

        var patt = new RegExp( '\\s' +
                removals.
                    replace( /\*/g, '[A-Za-z0-9-_]+' ).
                    split( ' ' ).
                    join( '\\s|\\s' ) +
                '\\s', 'g' );

        self.each(function(i, it) {
            var cn = ' ' + it.className + ' ';
            while(patt.test(cn)) {
                cn = cn.replace( patt, ' ' );
            }
            it.className = $.trim(cn);
        });

        return !additions ? self : self.addClass(additions);
    }
});
