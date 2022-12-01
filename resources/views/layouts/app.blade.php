<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="" />
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="Reconcilation" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}|@yield('title')</title>
    <link rel="canonical" href="" />
    <link rel="shortcut icon" href='{{asset("admin-panel/assets/media/logos/favicon.png")}}' />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href='{{asset("admin-panel/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css")}}' rel="stylesheet" type="text/css" />
    <link href='{{asset("admin-panel/assets/plugins/global/plugins.bundle.css")}}' rel="stylesheet" type="text/css" />
    <link href='{{asset("admin-panel/assets/css/style.bundle.css")}}' rel="stylesheet" type="text/css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            <!--begin::Aside-->
            @include('layouts.sidebar')
            <!--end::Aside-->
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <!--begin::Header-->
                <!-- layouts.navigation -->
                @include('layouts.navigation')
                <!--end::Header-->
                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <div class="toolbar" id="kt_toolbar">
                        <!--begin::Container-->
                        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                            <!--begin::Page title-->
                            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                                <!--begin::Title-->
                                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                                    <!--begin::Separator-->
                                    <span class="h-20px border-gray-200 border-start ms-3 mx-2">@yield('toolbar title')</span>
                                    <!--end::Separator-->
                                    <!--begin::Description-->
                                    {{-- <small class="text-muted fs-7 fw-bold my-1 ms-1">#XRS-45670</small> --}}
                                    <!--end::Description-->
                                </h1>
                                <!--end::Title-->
                            </div>
                        </div>
                        <!--end::Container-->
                    </div>
                    @yield('content')
                </div>
                <!--end::Content-->
                <!--begin::Footer-->
                <div class="footer py-4  flex-lg-column" id="kt_footer">
                    @include('layouts.footer')
                </div>
                <!--end::Footer-->
            </div>
        </div>
    </div>
    <script>
        var hostUrl = "/assets/";
    </script>
    <script src='{{asset("admin-panel/assets/plugins/global/plugins.bundle.js")}}'></script>
    <script src='{{asset("admin-panel/assets/js/scripts.bundle.js")}}'></script>
    <script src='{{asset("admin-panel/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js")}}'></script>
    @yield('footer-scripts')
    {{-- <script src={{asset("admin-panel/assets/js/custom/widgets.js")}}></script>
    <script src={{asset("admin-panel/assets/js/custom/apps/chat/chat.js")}}></script>
    <script src={{asset("admin-panel/assets/js/custom/modals/create-app.js")}}></script>
    <script src={{asset("admin-panel/assets/js/custom/modals/upgrade-plan.js")}}></script> --}}
</body>
</html>