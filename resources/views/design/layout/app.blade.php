<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Drazamed</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

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
        <link rel="stylesheet" href="/css/bootstrap.min.css"/>

        <!-- FlexboxGrid -->
        <link rel="stylesheet" href="/css/flexboxgrid.min.css">


        <!-- Estilos propios del proyecto -->
        <link rel="stylesheet" href="/css/header.css">
        <link rel="stylesheet" href="/css/footer.css">
        <link rel="stylesheet" href="/css/dropbox.css">
        <link rel="stylesheet" href="/assets/fonts/fontawesome/css/fontawesome.css">
        <link rel="stylesheet" href="/assets/fonts/fontawesome/css/solid.css">
        <link rel="stylesheet" href="/css/fonts.css">
        <link rel="stylesheet" href="/css/drazamed.css">

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
