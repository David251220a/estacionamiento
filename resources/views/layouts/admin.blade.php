<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from designreset.com/cork/ltr/demo4/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 25 Feb 2022 04:52:23 GMT -->
<head>
    @include('layouts.principal.estilos')
    @yield('styles')
    @livewireStyles
</head>

<body>

    @include('layouts.principal.navegacion')

    <!--  BEGIN NAVBAR  -->
    @include('layouts.principal.navegacion_2')
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <div class="sidebar-wrapper sidebar-theme">
            @include('layouts.principal.sidebar')
        </div>
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="row layout-top-spacing">
                    @if ($errors->any())
                        <div class="widget-content widget-content-area" style="padding: 0px">
                            <div class="alert alert-icon-left alert-light-danger mt-4" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>
                                            {{ $error }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    @if(session()->has('message'))
                        <div class="mt-10" style="border: rgb(25, 66, 25) 2px solid; margin-top:25px;border-radius:10px
                            ;margin-left:5px;background:rgb(25, 66, 25); color:white;font-size:20px">
                            <p class="px-4">{{ session()->get('message') }}</p>
                        </div>
                    @endif

                    @yield('content')

                </div>
            </div>
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">Cjppm © 2025 <a target="_blank" href="https://designreset.com/">Dirección de TIC</a>, All rights reserved.</p>
                </div>
            </div>
        </div>
        <!--  END CONTENT AREA  -->

    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    @include('layouts.principal.footer')
    <!-- END GLOBAL MANDATORY SCRIPTS -->
</body>

<!-- Mirrored from designreset.com/cork/ltr/demo4/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 25 Feb 2022 04:56:53 GMT -->
</html>
