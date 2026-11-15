<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login — PrintNexus</title>

    <!-- Bootstrap -->
    <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('assets/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{ asset('assets/vendors/animate.css/animate.min.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('assets/build/css/custom.min.css') }}" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        {{-- ── LOGIN FORM ── --}}
        <div class="animate form login_form">
          <section class="login_content">
            <form action="{{ route('login') }}" method="POST">
              @csrf

              <h1>Login Form</h1>

              {{-- Error message --}}
              @if ($errors->any())
                <div class="alert alert-danger" style="margin-bottom: 15px;">
                  @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                  @endforeach
                </div>
              @endif

              <div>
                <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus>
              </div>

              <div>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
              </div>

              <div>
                <button type="submit" class="btn btn-default submit">Log in</button>
                <a class="reset_pass" href="#">Lost your password?</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">New to site?
                  <a href="#signup" class="to_register"> Create Account </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-paw"></i> PrintNexus</h1>
                  <p>©2026 PrintNexus. All Rights Reserved.</p>
                </div>
              </div>
            </form>
          </section>
        </div>

        {{-- ── REGISTER FORM ── --}}
        <div id="register" class="animate form registration_form">
          <section class="login_content">
            <form action="{{ route('register') }}" method="POST">
              @csrf
              <h1>Create Account</h1>

              {{-- Error message --}}
              @if ($errors->any())
                <div class="alert alert-danger" style="margin-bottom: 15px;">
                  @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                  @endforeach
                </div>
              @endif

              <div>
                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}" required>
              </div>
              <div>
                <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
              </div>
              <div>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
              </div>
              <div>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
              </div>
              <div>
                <button type="submit" class="btn btn-default submit">Submit</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Already a member ?
                  <a href="#signin" class="to_register"> Log in </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-paw"></i> PrintNexus</h1>
                  <p>©2026 PrintNexus. All Rights Reserved.</p>
                </div>
              </div>
            </form>
          </section>
        </div>

      </div>
    </div>
  </body>
</html>