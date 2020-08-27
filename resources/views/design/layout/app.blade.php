<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Drazamed</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <script src="/js/app.js"></script>

        {{-- <script src="/js/app.js"></script> --}}
        <!-- jQuery-3.5 -->
        <script src="/js/jquery-3.5.1.js"></script>
        <!-- // jQuery UI -->
        <script src="/js/jquery-ui.js"></script>
        <link rel="stylesheet" href="/css/jquery-ui.css">

        <!-- Money format for JS -->
        <script src="/js/simple.money.format.js"></script>

        <!-- Tooltip & Popover -->
        <script src="/js/popper.min.js"></script>

        <!-- Bootstrap 4 -->
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/bootbox.all.min.js"></script>
        {{-- <link rel="stylesheet" href="/css/app.css"/> --}}

        <link rel="stylesheet" href="/css/bootstrap.min.css"/>

        <!-- FlexboxGrid -->
        <link rel="stylesheet" href="/css/flexboxgrid.min.css">


        <!-- Estilos propios del proyecto -->
        <link rel="stylesheet" href="/css/app.css">

        {{-- <link rel="stylesheet" href="/css/header.css">
        <link rel="stylesheet" href="/css/login.css" />
        <link rel="stylesheet" href="/css/search-form.css">
        <link rel="stylesheet" href="/css/footer.css">
        <link rel="stylesheet" href="/css/dropbox.css">

        <link rel="stylesheet" href="/css/drazamed.css"> --}}

        <link rel="stylesheet" href="/assets/fonts/fontawesome/css/fontawesome.css">
        <link rel="stylesheet" href="/assets/fonts/fontawesome/css/solid.css">
        <link rel="stylesheet" href="/css/fonts.css">


        <!-- Google Analytics -->
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-C32TYXD8C1"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'G-C32TYXD8C1');
        </script>

        @yield('custom-css')

        <style>
            .cookie-consent {
                background-color: #015670;
                color: white;
                text-align: center;
                padding: 10px;
            }


        </style>

        <!-- resources/views/chat.blade.php -->
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
                height: 350px;
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
    </head>
    <body>
        @include('design.layout.header')
        @yield('content')

        <script src="/js/modal_manager.js"></script>
        <script src="/js/design.js"></script>
        <script src="/js/mobile_menu.js"></script>


        @yield('custom-js')
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
@include('design.modals.chat')
@include('cookieConsent::index')
