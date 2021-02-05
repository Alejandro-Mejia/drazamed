


// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.
importScripts('https://www.gstatic.com/firebasejs/8.2.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.2.1/firebase-messaging.js');
importScripts('https://www.gstatic.com/firebasejs/8.2.1/firebase-analytics.js');

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
// Initialize the Firebase app in the service worker by passing in the
// messagingSenderId.
firebase.initializeApp({
    messagingSenderId: "193162804196",
    apiKey: "AIzaSyBvFM0v-DCmxGBYwVU-Fi6r_rUkQRBi57U",
    projectId: "drazamedapp",
    appId: "1:193162804196:web:5514e23878a8fb473425f1",
});
// var firebaseConfig = {
//     apiKey: "AIzaSyBvFM0v-DCmxGBYwVU-Fi6r_rUkQRBi57U",
//     authDomain: "drazamedapp.firebaseapp.com",
//     databaseURL: "https://drazamedapp-default-rtdb.firebaseio.com",
//     projectId: "drazamedapp",
//     storageBucket: "drazamedapp.appspot.com",
//     messagingSenderId: "193162804196",
//     appId: "1:193162804196:web:5514e23878a8fb473425f1",
//     measurementId: "G-YQJ9QT2Y8Z"
// };

// firebase.initializeApp(config);

//  firebase.analytics();

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();

// messaging.setBackgroundMessageHandler(function(payload) {
//     console.log(
//         "[firebase-messaging-sw.js] Received background message ",
//         payload,
//     );
//     // Customize notification here
//     const notificationTitle = "Background Message Title";
//     const notificationOptions = {
//         body: "Drazamed tiene un mensaje importante para ti.",
//         icon: "//assets/img/logo.png",
//     };

//     return self.registration.showNotification(
//         notificationTitle,
//         notificationOptions,
//     );
// });

//  const messaging = firebase.messaging();
// messaging
// .requestPermission()
// .then(function () {
//     MsgElem.innerHTML = "Notification permission granted."
//     console.log("Notification permission granted.");

//     // get the token in the form of promise
//     token = messaging.getToken()
//     console.log(token)
//     return token
// })
// .then(function(token) {
//     // print the token on the HTML page
//     TokenElem.innerHTML = "token is : " + token
//     console.log("Token FCM: " . token);
// })
// .catch(function (err) {
// ErrElem.innerHTML = ErrElem.innerHTML + "; " + err
// console.log("Unable to get permission to notify.", err);
// });

// const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
console.log('[firebase-messaging-sw.js] Received background message ', payload);
// const notificationTitle = 'Background Message from html';
// const notificationOptions = {
// body: 'Background Message body.'
// // icon: '/assets/images/logo2.png'
// };

return self.registration.showNotification(notificationTitle,
    notificationOptions);
});

