<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
  <title>Login</title>
  <link rel="stylesheet" href="{{asset('css/reset.css')}}" />
  <link rel="stylesheet" href="{{asset('css/signup.css')}}" />
</head>

<body>
  <div class="singup">
    <h1 class="signup-heading">Login</h1>
    @if (session('warning') || session('error'))
    <span class="error">
      <strong>{{ session('warning') ?? session('error') }}</strong>
    </span>
    @endif
    <form action="{{route('post.login')}}" class="signup-form" autocomplete="off" method="POST">
      @csrf
      <div class="form-input">
        <label for="email" class="signup-label"> Email</label>
        <input type="email" id="email" class="signup-input" placeholder="email" name="email" required>
      </div>
      <div class="form-input">
        <label for="name" class="signup-label"> Password</label>
        <input type="password" id="password" class="signup-input" placeholder="password" name="password" required>
      </div>
      <div class="check">
        <div class="remember-me">
          <input type="checkbox" class="checkbox" name="remember_me" id="checkbox">
          <label for="checkbox" class="signup-label">Remember me</label>
        </div>
        <a href="{{route('forgot-password')}}" class="signup-login-link">Forgot your password?</a>
      </div>
      <button type="submit" class="signup-submit">Login</button>
    </form>
    <div class="signup-or"><span>OR</span></div>
    <form action="{{route('login-google')}}" method="post">
      @csrf
      <button class="signup-social" type="submit">
        <img class="signup-social-icon" alt="Continue with Google" src="https://cdn.monday.com/images/google-icon.svg">
        <span class="signup-social-text">Login with Google</span>
      </button>
    </form>
    <p class="signup-already">Don't have an account yet? <a href="{{route('signup')}}" class="signup-login-link">Signup</a></p>
  </div>
</body>

</html>