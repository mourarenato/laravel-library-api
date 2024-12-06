<html>
    <head>
    @php
        $name = "Bobby";
    @endphp
        <title>App Name - {{ $ola }}!</title>
    </head>
    <body>
        @section('sidebar')
            This is the master sidebar.
        @show

        <div class="container">
            @yield('content')
        </div>
    </body>
</html>
