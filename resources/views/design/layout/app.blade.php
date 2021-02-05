<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Drazamed</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">


        {{-- Cargar los js de la aplicacion --}}
        <script src="/dist/app.js"></script>
        <script src="/js/design.js"></script>

        {{-- <!-- Money format for JS -->
        <script src="/js/simple.money.format.js"></script>

        <!-- Tooltip & Popover -->
        <script src="/js/popper.min.js"></script>

        <!-- Bootstrap 4 -->
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/bootbox.all.min.js"></script> --}}
        <link rel="stylesheet" href="/dist/app.css">
        <link rel="stylesheet" href="/assets/fonts/fontawesome/css/solid.css">
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-C32TYXD8C1"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'G-C32TYXD8C1');
        </script>

        @yield('custom-css')

        <link rel="stylesheet" href="/css/mobile.css">

        <style>
            .cookie-consent {
                background-color: #015670;
                color: white;
                text-align: center;
                padding: 10px;
            }


        </style>

        <style>
            .chat {
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .chat li {
                margin-bottom: 10px;
                padding-bottom: 5px;
                border-bottom: 1px dotted #B3A9A9;
            }

            .chat li .chat-body p {
                margin: 0;
                color: #777777;
            }

            .panel-body {
                overflow-y: scroll;
                height: 300px;
            }

            ::-webkit-scrollbar-track {
                -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
                background-color: #F5F5F5;
            }

            ::-webkit-scrollbar {
                width: 12px;
                background-color: #F5F5F5;
            }

            ::-webkit-scrollbar-thumb {
                -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
                background-color: #555;
            }
        </style>

        <script src="https://www.gstatic.com/firebasejs/8.2.5/firebase-app.js"></script>
        <script src="https://www.gstatic.com/firebasejs/8.2.3/firebase-messaging.js"></script>
        <script src="https://www.gstatic.com/firebasejs/8.2.5/firebase-analytics.js"></script>

        {{-- <script src="/firebase-messaging-sw.js"> </script> --}}
        <script>
            var tokenFCM;
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

            messaging.onMessage(function(payload) {
                console.log("Message received. ", payload);
                // alert(payload.notification.body);
                // bootbox.confirm({
                //     title: payload.notification.title,
                //     message: payload.notification.body,
                //     buttons: {
                //         cancel: {
                //             label: '<i class="fa fa-times"></i> Cancel'
                //         },
                //         confirm: {
                //             label: '<i class="fa fa-check"></i> Confirm'
                //         }
                //     },
                //     callback: function (result) {
                //         console.log('This was logged in the callback: ' + result);
                //     }
                // });
                // alert(payload.notification)
                // ...
                bootbox.alert({
                    title: payload.notification.title,
                    message: payload.notification.body,
                });
            });

        </script>

    </head>
    <body>
        <div id="token"></div>
        <div id="msg"></div>
        <div id="notis"></div>
        <div id="err"></div>
        <script>
            MsgElem = document.getElementById("msg")
            TokenElem = document.getElementById("token")
            NotisElem = document.getElementById("notis")
            ErrElem = document.getElementById("err")
        </script>

        @include('design.layout.header')


        @yield('content')


        <script src="/js/modal_manager.js"></script>
        {{-- <script src="/js/mobile_menu.js"></script> --}}

        @yield('custom-js')

        {{-- <script src="/js/design.js"></script> --}}
    </body>


    <footer>
        @include('design.layout.footer')
    </footer>
</html>


@include('design.modals.msg')
@include('design.modals.login')
@include('design.modals.register')
@include('design.modals.recovery')
@include('design.modals.pinfo')
{{-- @include('design.modals.chat') --}}
@include('cookieConsent::index')
