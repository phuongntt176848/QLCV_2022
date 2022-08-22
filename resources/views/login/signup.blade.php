<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
  <title>Signup</title>
  <link rel="stylesheet" href="{{asset('css/reset.css')}}" />
  <link rel="stylesheet" href="{{asset('css/signup.css')}}" />
</head>

<body>
  <div class="singup">
    <h1 class="signup-heading">Sign up</h1>
    @if (session('message'))
    <span class="message">
      <strong>{{ session('message') }}</strong>
    </span>
    @endif
    <form action="{{route('post.signup')}}" class="signup-form" autocomplete="off" method="POST">
      @csrf
      <div class="form-input">
        <label for="name" class="signup-label"> Name</label>
        <input type="text" id="name" class="signup-input" placeholder="Please enter your name" name="name" required value="{{old('name')}}">
        @if ($errors->has('name'))
        <span class="help-block">
          <strong>{{ $errors->first('name') }}</strong>
        </span>
        @endif
      </div>
      <div class="form-input">
        <label for="email" class="signup-label"> Email</label>
        <input type="email" id="email" class="signup-input" placeholder="Please enter your email" name="email" required value="{{old('email')}}">
        @if ($errors->has('email'))
        <span class="help-block">
          <strong>{{ $errors->first('email') }}</strong>
        </span>
        @endif
      </div>
      <div class="form-input">
        <label for="password" class="signup-label"> Password</label>
        <input type="password" id="email" class="signup-input" placeholder="Please enter your password" name="password" required value="{{old('password')}}">
        @if ($errors->has('password'))
        <span class="help-block">
          <strong>{{ $errors->first('password') }}</strong>
        </span>
        @endif
      </div>
      <button class="signup-submit" type="submit">Sign up</button>
    </form>
    <div class="signup-or"><span>OR</span></div>
    <form action="{{route('login-google')}}" method="post">
      @csrf
      <button class="signup-social" type="submit">
        <img class="signup-social-icon" alt="Continue with Google" src="https://cdn.monday.com/images/google-icon.svg">
        <span class="signup-social-text">Sign up with Google</span>
      </button>
    </form>
    <p class="signup-already">Already have an account? <a href="{{route('login')}}" class="signup-login-link">Log In</a></p>

  </div>
</body>

</html>