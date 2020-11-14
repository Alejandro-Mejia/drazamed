/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import $ from 'jquery';
window.$ = window.jQuery = $;
import 'jquery-ui/ui/widgets/datepicker.js';


// window.Vue = require('vue');


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
// Vue.component('chat-messages', require('./components/ChatMessages.vue').default);
// Vue.component('chat-form', require('./components/ChatForm.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#chat',

//     data: {
//         messages: [],
//         orders: []
//     },

//     created() {
//         this.fetchMessages();
//     },

//     methods: {
//         fetchMessages() {
//             axios.get('/messages').then(response => {
//                 this.messages = response.data;
//             });
//         },

//         addMessage(message) {
//             this.messages.push(message);

//             axios.post('/messages', message).then(response => {
//               console.log(response.data);
//             });
//         }
//     }

// });

import Echo from "laravel-echo";

Pusher.logToConsole = true;

window.Echo.channel('Drazamed')
//   .listen('.MessageSent', (e) => {
//     console.log(e);
//     app.messages.push({
//       message: e.message.message,
//       user: e.user
//     });
//   })
  .listen('.orderStatus', (e) => {
    console.log('Orden verificada ....: ' );
    console.log(e);
    // console.log(e.user.id);
    console.log("Verificando que sea para el usuario");
    httpGetAsync('/user/is-actual-user/' + e.user.id, checkedUser);
  });

function httpGetAsync(theUrl, callback)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
        {
            console.log(xmlHttp.responseText);
            callback(xmlHttp.responseText);
        }

    }
    xmlHttp.open("GET", theUrl, true); // true for asynchronous
    xmlHttp.send();
}

function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function checkedUser(data) {

    console.log("Usuario : ");

    console.log("isJson:" + IsJsonString(data));

    var dataJson = JSON.parse(data);
    // dataJson = data;
    // console.log('Status' + data['status']);
    console.log(dataJson.status);

    if (dataJson.status == "SUCCESS") {
        var ask = window.confirm("Tu orden ha sido verificada, desde tu perfil podras pagar a partir de este momento. Gracias por confiar en Drazamed");
        if (ask) {
            // window.alert("This post was successfully deleted.");

            window.location.href = "/account-page";

        }
        // alert('Tu orden ha sido verificada, desde tu perfil podras pagar a partir de este momento. Gracias por confiar en Drazamed');
    } else {
        //alert('Mensaje para otro usuario');
    }

}


