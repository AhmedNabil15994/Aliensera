<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AlienSera | Login </title>
    <link rel="icon" href="{{ URL::asset('assets/images/logo.jpeg') }}" type="image/ico" />
    <!-- Bootstrap -->
    <link href="{{ URL::asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ URL::asset('assets/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ URL::asset('assets/vendors/nprogress/nprogress.css')}}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{ URL::asset('assets/vendors/animate.css/animate.min.css')}}" rel="stylesheet">

    <link rel="stylesheet" href="{{ URL::asset('assets/production/css/toastr.min.css')}}">

    <!-- Custom Theme Style -->
    <link href="{{ URL::asset('assets/build/css/customLogin.css')}}" rel="stylesheet">
    @include('Partials.notf_messages')
  </head>

  <body class="login">
      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
              <form method="post" action="{{ URL::to('/login') }}">
                {!! csrf_field() !!}
              <img src="{{ URL::asset('assets/images/logo.jpeg') }}" height="100" alt="" />
              <br>
              <br>
              <span>Login to AlienSera</span>
              <br>
              <br>
              <div>
                <input type="text" class="form-control" placeholder="Email" name="email" required="" />
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Password" name="password" required="" />
              </div>
              <div>
                <button class="btn btn-info col-md-12 submit" type="submit">Log in</button>
              </div>
              <br>
              <br>
              <div class="separator">
                <br />
                <div>
                  <h1> AlienSera</h1>
                  <p>©{{ date('Y') }} All Rights Reserved.</p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
  </body>
</html>
<script  src="{{ URL::asset('assets/vendors/jquery/dist/jquery.min.js')}}"></script>
<script src="{{ URL::asset('assets/production/js/toastr.min.js')}}"></script>
