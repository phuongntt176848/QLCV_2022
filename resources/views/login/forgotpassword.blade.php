<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
  <title>Forgot Password</title>
  <link rel="stylesheet" href="{{asset('css/reset.css')}}" />
  <link rel="stylesheet" href="{{asset('css/signup.css')}}" />
</head>

<body>
  <div class="singup">
    <h1 class="signup-heading">Forgot Password</h1>
    @if (session('message'))
    <span class="message">
      <strong>{{ session('message') }}</strong>
    </span>
    @endif
    @if (session('warning') || session('error'))
    <span class="error">
      <strong>{{ session('warning') ?? session('error') }}</strong>
    </span>
    @endif
    <form action="{{route('post.forgot-password')}}" class="signup-form" autocomplete="off" method="POST">
      @csrf
      <div class="form-input">
        <label for="email" class="signup-label"> Email</label>
        <input type="email" id="email" class="signup-input" placeholder="Enter your email address" name="email" required>
      </div>
      <button type="submit" class="signup-submit">Continue</button>
    </form>
  </div>
</body>

</html>