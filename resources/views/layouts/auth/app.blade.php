<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>{{ Config('app.title') }}| @yield('title') </title>
    <link rel="icon" type="image/x-icon" href="{{ asset('admin-assets/assets/img/favicon.ico')}}"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('admin-assets/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-assets/assets/css/plugins.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-assets/assets/css/authentication/form-1.css')}}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/forms/theme-checkbox-radio.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/forms/switches.css')}}">
</head>
<body class="form">
    

    <div class="form-container">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                   @yield('content')
                </div>
            </div>
        </div>
        <div class="form-image">
            <div class="l-image">
            </div>
        </div>
    </div>

    
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('admin-assets/assets/js/libs/jquery-3.1.1.min.js')}}"></script>
    <script src="{{ asset('admin-assets/bootstrap/js/popper.min.js')}}"></script>
    <script src="{{ asset('admin-assets/bootstrap/js/bootstrap.min.js')}}"></script>
    
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('admin-assets/assets/js/authentication/form-1.js')}}"></script>

</body>
</html>