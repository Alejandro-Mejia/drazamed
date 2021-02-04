importScripts("https://www.gstatic.com/firebasejs/8.2.5/firebase-app.js");
importScripts(
    "https://www.gstatic.com/firebasejs/8.2.5/firebase-messaging.js",
);
// For an optimal experience using Cloud Messaging, also add the Firebase SDK for Analytics.
importScripts(
    "https://www.gstatic.com/firebasejs/8.2.5/firebase-analytics.js",
);

// Initialize the Firebase app in the service worker by passing in the
// messagingSenderId.
// firebase.initializeApp({
//     messagingSenderId: "193162804196",
//     apiKey: "AIzaSyBvFM0v-DCmxGBYwVU-Fi6r_rUkQRBi57U",
//     projectId: "drazamedapp",
//     appId: "1:193162804196:web:5514e23878a8fb473425f1",
// });
var firebaseConfig = {
    apiKey: "AIzaSyBvFM0v-DCmxGBYwVU-Fi6r_rUkQRBi57U",
    authDomain: "drazamedapp.firebaseapp.com",
    databaseURL: "https://drazamedapp-default-rtdb.firebaseio.com",
    projectId: "drazamedapp",
    storageBucket: "drazamedapp.appspot.com",
    messagingSenderId: "193162804196",
    appId: "1:193162804196:web:5514e23878a8fb473425f1",
    measurementId: "G-YQJ9QT2Y8Z"
};

firebase.initializeApp(firebaseConfig);
firebase.analytics();

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload,
    );
    // Customize notification here
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Drazamed tiene un mensaje importante para ti.",
        icon: "//assets/img/logo.png",
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );
});

//  const messaging = firebase.messaging();
 messaging
   .requestPermission()
   .then(function () {
     MsgElem.innerHTML = "Notification permission granted."
     console.log("Notification permission granted.");

     // get the token in the form of promise
     return messaging.getToken()
   })
   .then(function(token) {
     // print the token on the HTML page
     TokenElem.innerHTML = "token is : " + token
     console.log("Token FCM: " . token);
   })
   .catch(function (err) {
   ErrElem.innerHTML = ErrElem.innerHTML + "; " + err
   console.log("Unable to get permission to notify.", err);
 });
