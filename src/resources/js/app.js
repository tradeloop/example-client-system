let token = document.head.querySelector('meta[name="csrf-token"]')

import Vue from 'vue'
import VueCodemirror from 'vue-codemirror'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
Vue.use(BootstrapVue);
Vue.use(VueCodemirror, /* {
  options: { theme: 'base16-dark', ... },
  events: ['scroll', ...]
} */);

window.beautifyJS = require('js-beautify').js_beautify

Vue.component('alerts', require('./components/alerts/Alerts.vue').default);
Vue.component('device-list', require('./components/Devices/DeviceList.vue').default);
Vue.component('obit-list', require('./components/Devices/ObitsList.vue').default);
Vue.component('device-detail', require('./components/Devices/DeviceDetail.vue').default);
Vue.component('device-obit-detail', require('./components/Devices/DeviceObitDetail.vue').default);
Vue.component('obit-detail', require('./components/Devices/ObitDetail.vue').default);
Vue.component('edit-device', require('./components/Devices/EditDevice.vue').default);
Vue.component('usn-generator', require('./components/Utils/GenerateUsn.vue').default);
Vue.component('checksum-generator', require('./components/Utils/GenerateChecksum.vue').default);
Vue.component('device-row', require('./components/Devices/DeviceRow.vue').default);
Vue.component('obit-mapper', require('./components/Devices/ObitMapper.vue').default);
Vue.component('structured-data-row', require('./components/Devices/StructuredDataRow.vue').default);

window.Events = new Vue({})

window._app = new Vue({
    el: '#app',
    //store,
    data: {
        isMobile: false,
        alerts: []
    },
    mounted: function() {
        if (this.screen().width <= 768) {
            this.isMobile = true
        }
        Events.$on('sendAlert',(t) => {
            this.notify(t);
        });
    },
    methods: {
        toSlug (str) {
            str = str.replace(/^\s+|\s+$/g, ''); // trim
            str = str.toLowerCase();

            // remove accents, swap ñ for n, etc
            var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
            var to   = "aaaaeeeeiiiioooouuuunc------";
            for (var i=0, l=from.length ; i<l ; i++) {
                str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
            }

            str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                .replace(/\s+/g, '-') // collapse whitespace and replace by -
                .replace(/-+/g, '-'); // collapse dashes

            return str;
        },
        dismissAlert: function(k) {
            if(this.alerts) {
                let indexToRemove = -1;
                this.alerts.forEach((a,i)=>{
                    if(a.key === k) {
                        indexToRemove = i;
                    }
                });
                if(indexToRemove !== -1) {
                    this.alerts.splice(indexToRemove,1);
                }
            }
        },
        notify: function(data){
            let d = {message:'',type:'error',autoclose:false,onClose:()=>{}};
            let alert_data = Object.assign({},d,data);
            let key = guid();
            alert_data.key = key;
            this.alerts.push(alert_data);

            if(alert_data.autoclose) {
                setTimeout(()=>{
                    if(this.alerts) {
                        let indexToRemove = -1;
                        this.alerts.forEach((a,i)=>{
                            if(a.key === key) {
                                indexToRemove = i;
                            }
                        });
                        if(indexToRemove !== -1) {
                            this.alerts.splice(indexToRemove,1);
                        }
                    }
                },5000);
            }
        },
        screen: function() {
            var myWidth = 0,
                myHeight = 0
            if (typeof(window.innerWidth) == 'number') {
                //Non-IE
                myWidth = window.innerWidth
                myHeight = window.innerHeight
            } else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
                //IE 6+ in 'standards compliant mode'
                myWidth = document.documentElement.clientWidth
                myHeight = document.documentElement.clientHeight
            } else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
                //IE 4 compatible
                myWidth = document.body.clientWidth
                myHeight = document.body.clientHeight
            }
            return {
                height: myHeight,
                width: myWidth
            }
        },
        logout: function(){

            axios('/api/internal/logout', {
                method:'post',
                data: {},
                responseType: 'json',
            })
                .then((response) => {
                    window.location = '/';
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.notify({
                        message: 'Something went wrong.',
                        type: 'error',
                        autoclose: true
                    });
                });
        },
        copyToClipboard: function(text) {
            var dummy = document.createElement("textarea");
            // to avoid breaking orgain page when copying more words
            // cant copy when adding below this code
            // dummy.style.display = 'none'
            document.body.appendChild(dummy);
            //Be careful if you use texarea. setAttribute('value', value), which works with "input" does not work with "textarea". – Eduard
            dummy.value = text;
            dummy.select();
            document.execCommand("copy");
            document.body.removeChild(dummy);
        }
    }
})
