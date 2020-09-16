<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Drazamed</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="x-ua-compatible" content="ie=edge">


        {{-- Cargar los js de la aplicacion --}}
        <script src="/js/app.js"></script>

        <!-- Money format for JS -->
        <script src="/js/simple.money.format.js"></script>

        <!-- Tooltip & Popover -->
        <script src="/js/popper.min.js"></script>

        <!-- Bootstrap 4 -->
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/bootbox.all.min.js"></script>
        <link rel="stylesheet" href="/css/app.css">
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
    </head>
    <body>
        @include('design.layout.header')


        @yield('content')


        <script src="/js/modal_manager.js"></script>
        <script src="/js/mobile_menu.js"></script>

        @yield('custom-js')

        <script src="/js/design.js"></script>
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
