<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Drazamed</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <link
            rel="stylesheet"
            href="/css/bootstrap.min.css"
        />

        <link rel="stylesheet" href="/css/flexboxgrid.min.css">
        <script
            src="/js/jquery-3.4.1.slim.min.js"
        ></script>

        <script src="/js/simple.money.format.js"></script>


        <!-- <script src="{{ mix('js/app.js') }}"></script>
        <script src="{{ mix('css/app.css') }}"></script> -->
        <!-- // jQuery UI -->
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <link rel="stylesheet" href="/css/jquery-ui.css">


        <script
            src="/js/popper.min.js"
        ></script>
        <script
            src="/js/bootstrap.min.js"
        ></script>

        <link rel="stylesheet" href="/css/header.css">
        <link rel="stylesheet" href="/css/footer.css">

        <link rel="stylesheet" href="/assets/fonts/fontawesome/css/fontawesome.css">
        <link rel="stylesheet" href="/assets/fonts/fontawesome/css/solid.css">
        <link rel="stylesheet" href="/css/fonts.css">
        <link rel="stylesheet" href="/css/drazamed.css">

        @yield('custom-css')
    </head>
    <body>
        @include('design.layout.header')
        @yield('content')
        @include('design.layout.footer')

        <script src="/js/modal_manager.js"></script>
        <script src="/js/design.js"></script>
        <script src="/js/mobile_menu.js"></script>


        @yield('custom-js')
    </body>
</html>
