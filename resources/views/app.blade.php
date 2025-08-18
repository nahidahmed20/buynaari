<!DOCTYPE html>
<html lang="en">

<head>
     <!-- Title Meta -->
     <meta charset="utf-8" />
     <title>Dashboard | Larkon - Responsive Admin Dashboard Template</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="description" content="A fully responsive premium admin dashboard template" />
     <meta name="author" content="Techzaa" />
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">


     <!-- App favicon -->
     <link rel="shortcut icon" href="{{asset('backend/assets/images/favicon.ico')}}">

     <!-- Vendor css (Require in all Page) -->
     <link href="{{asset('backend/assets/css/vendor.min.css')}}" rel="stylesheet" type="text/css" />

     <!-- Icons css (Require in all Page) -->
     <link href="{{asset('backend/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />

     <!-- App css (Require in all Page) -->
     <link href="{{asset('backend/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />

     <!-- Theme Config js (Require in all Page) -->
     <script src="{{asset('backend/assets/js/config.js')}}"></script>
     @routes
      @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
      @inertiaHead
</head>



    <body class="font-sans antialiased">
        @inertia
    </body>
  <!-- Vendor Javascript (Require in all Page) -->
     <script src="{{asset('backend/assets/js/vendor.js')}}"></script>

     <!-- App Javascript (Require in all Page) -->
     <script src="{{asset('backend/assets/js/app.js')}}"></script>

     <!-- Vector Map Js -->
     <script src="{{asset('backend/assets/vendor/jsvectormap/js/jsvectormap.min.js')}}"></script>
     <script src="{{asset('backend/assets/vendor/jsvectormap/maps/world-merc.js')}}"></script>
     <script src="{{asset('backend/assets/vendor/jsvectormap/maps/world.js')}}"></script>

     <!-- Dashboard Js -->
     <script src="{{asset('backend/assets/js/pages/dashboard.js')}}"></script>
</html>
