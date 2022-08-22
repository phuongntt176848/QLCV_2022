<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
  <title>New Password</title>
  <link rel="stylesheet" href="{{asset('css/reset.css')}}" />
  <link rel="stylesheet" href="{{asset('css/signup.css')}}" />
</head>

<body>
  <div class="singup">
    <h1 class="signup-heading">Change Password</h1>
    <form action="{{route('update-password')}}" class="signup-form" autocomplete="off" method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" name="email" value="{{$email}}">
      <input type="hidden" name="token" value="{{$token}}">
      <div class="form-input">
        <label for="newpassword" class="signup-label"> New password</label>
        <input type="password" id="newpassword" class="signup-input" placeholder="Creat new password" name="password" required>
        @if ($errors->has('password'))
        <span class="help-block">
          <strong>{{ $errors->first('password') }}</strong>
        </span>
        @endif
      </div>
      <div class="form-input">
        <label for="confirmpassword" class="signup-label"> Confirm password</label>
        <input type="password" id="confirmpassword" class="signup-input" placeholder="Confirm your password" name="password_confirmation" required>
        @if ($errors->has('password_confirmation'))
        <span class="help-block">
          <strong>{{ $errors->first('password_confirmation') }}</strong>
        </span>
        @endif
      </div>
      <button type="submit" class="signup-submit">Change</button>
    </form>
  </div>
</body>

</html>