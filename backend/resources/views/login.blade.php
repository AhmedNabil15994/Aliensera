<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AlienSera | Login </title>
    <link rel="icon" href="{{ asset('assets/images/logo.jpeg') }}" type="image/ico" />
    <!-- Bootstrap -->
    <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('assets/vendors/nprogress/nprogress.css')}}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{ asset('assets/vendors/animate.css/animate.min.css')}}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/production/css/toastr.min.css')}}">

    <!-- Custom Theme Style -->
    <link href="{{ asset('assets/build/css/custom.min.css')}}" rel="stylesheet">
    @include('Partials.notf_messages')
    <style type="text/css" media="screen">
      div.animate img{
        display: block;
        margin: auto;
      }
      .btn.submit{
        width: 100%;
      }
      div.mg-b-15{
        margin-bottom: 20px;
      }
      div.col-xs-6:first-of-type{
        padding-left: 0;
      }
      div.col-xs-6:nth-of-type(2){
        padding-right: 0;
      }
    </style>
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <img src="{{ asset('assets/images/logo.jpeg') }}" height="100" alt=""/>
          <section class="login_content">
            <form method="post" action="{{ URL::to('/login') }}">
              {!! csrf_field() !!}
              <h1>Login To AlienSera</h1>
              <div>
                <input type="text" class="form-control" placeholder="Email" name="email" required="" />
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Password" name="password" required="" />
              </div>
              <div>
                <button class="btn btn-primary submit" type="submit">Log in</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">New to site ?
                  <a href="#signup" class="to_register"> Create Account </a>
                </p>
                {{-- <div class="clearfix"></div>

                <p class="change_link">Forget your password ?
                  <a class="to_register" href="{{ URL::to('/reset-password') }}">Reset it now</a>
                </p>
 --}}
                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-book"></i> AlienSera</h1>
                  <p>©{{ date('Y') }} All Rights Reserved.</p>
                </div>
              </div>
            </form>
          </section>
        </div>

        <div id="register" class="animate form registration_form">
          <img src="{{ asset('assets/images/logo.jpeg') }}" height="100" alt=""/>
          <section class="login_content">
            <form method="post" action="{{ URL::to('/register') }}">
              {!! csrf_field() !!}
              <h1>Create New Account</h1>
              <div class="col-xs-6">
                <input type="text" class="form-control" placeholder="First Name" required="" name="first_name" value="{{ old('first_name') }}" />
              </div>
              <div class="col-xs-6">
                <input type="text" class="form-control" placeholder="Last Name" required="" name="last_name" value="{{ old('last_name') }}" />
              </div>
              <div>
                <input type="email" class="form-control" placeholder="Email" required="" name="email" value="{{ old('email') }}" />
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Password" required="" name="password"/>
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Re-type password" required="" name="password_confirmation"/>
              </div>
              <div class="mg-b-15">
                <input type="tel" class="form-control" placeholder="Phone" required="" name="phone" value="{{ old('phone') }}" />
              </div>
              <div class="mg-b-15">
                <select name="gender" class="form-control" required>
                    <option value="">Choose Gender...</option>
                    <option value="1" {{ old('gender') == 1 ? 'selected' : '' }}>Male</option>
                    <option value="2" {{ old('gender') == 2 ? 'selected' : '' }}>Female</option>
                </select>
              </div>
              <div>
                <button class="btn btn-success submit">Register</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Already a member ?
                  <a href="#signin" class="to_register"> Log in </a>
                </p>
                {{-- <div class="clearfix"></div>

                <p class="change_link">Forget your password ?
                  <a class="to_register" href="{{ URL::to('/reset-password') }}">Reset it now</a>
                </p> --}}

                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-book"></i> AlienSera</h1>
                  <p>©{{ date('Y') }} All Rights Reserved.</p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>

</html>
<script  src="{{ asset('assets/vendors/jquery/dist/jquery.min.js')}}"></script>
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/production/js/toastr.min.js')}}"></script>
<script type="text/javascript">
  $(function(){
    $('select').select2();
  })
</script>
