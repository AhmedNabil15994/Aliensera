	<link rel="icon" href="{{ asset('assets/images/logo.jpeg') }}" type="image/ico" />
    <!-- Bootstrap -->
    <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('assets/vendors/nprogress/nprogress.css')}}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{ asset('assets/vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="{{ asset('assets/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/dropzone/dist/min/dropzone.min.css') }}" rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{ asset('assets/vendors/jqvmap/dist/jqvmap.min.css')}}" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="{{ asset('assets/vendors/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <!-- bootstrap-datetimepicker -->
    <link href="{{ asset('assets/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/production/css/toastr.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/css/fancybox/jquery.fancybox.css')}}" type="text/css" media="screen">

    <!-- editor -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/summernote/dist/summernote.css') }}">
    <link href="{{ asset('assets/plugins/lou-multi-select/css/multi-select.css')}}" rel="stylesheet">


    <!-- Custom Theme Style -->
    <link href="{{ asset('assets/build/css/custom.css')}}" rel="stylesheet">

    <script src="{{ asset('assets/components/header.js')}}"></script>

    <style>
        .imagesHolder {overflow: hidden;}
        td img{
            width: 50px;
            height: 50px;
            border: 1px solid #DDD;
            border-radius: 50%;
        }
        td a,h4.heading a{
            color: unset;
        }
        td a {
            font-size: 14px;
            font-weight: 600;
        }
        ul.paginationer{
            float: right;
            margin-top: -25px;
        }
        .child_menu li a span.badge{
            width: 20px;
            height: 20px;
            padding: 3px;
            border-radius: 50%;
            margin-left: 5px;
        }
        .imagesHolder figure {
            float: left;
            width: 250px;
            height: 250px;
            overflow: hidden;
            margin: 5px 10px 5px 0;
            position: relative;
            border: 1px solid #eee;
        }
        .imagesHolder figure img {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            min-height: 100%;
            min-width: 100%;
            max-width: 100%;
        }
        .imagesHolder figure .remove {
            position: absolute;
            top: 1px;
            right: 1px;
            background: #fff;
            border-radius: 0;
            color: #000;
            padding: 5px;
            z-index: 9;
        }
        .imagesHolder figure .imageFeatured {
            position: absolute;
            top: 1px;
            left: 1px;
            background: #fff;
            border-radius: 0;
            color: #000;
            padding: 5px;
            z-index: 9;
            width: 20px;
            height: 17px;
        }
        .box-title,
        .box-title a {
            display:block;
        }
        textarea{
            min-height: 150px;
            max-height: 150px;
            min-width: 100%;
            max-width: 100%;
        }
    </style>
