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
            integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
            crossorigin="anonymous"
        />
        <script
            src="/js/jquery-3.4.1.slim.min.js"
            integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
            crossorigin="anonymous"
        ></script>

        <!-- // jQuery UI -->
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


        <script
            src="/js/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"
        ></script>
        <script
            src="/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"
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
