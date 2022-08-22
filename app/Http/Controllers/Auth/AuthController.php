<?php

namespace App\Http\Controllers\Auth;

use App\Classes\ActivationService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected $redirectTo = '/login';

    protected $activationService;

    public function __construct(ActivationService $activationService)
    {
        $this->activationService = $activationService;
    }
    // Login
    public function view_login()
    {
        return view('login.login');
    }

    public function post_login(Request $req)
    {
        // validator
        $remember = ($req->has('remember')) ? true : false;
        $credentials = $req->only(['email', 'password']);
        $remember = $req->has('remember_me') ? true : false;

        $user = User::where('email', $credentials['email'])->first();

        if ($user && !$user->active) {
            $this->activationService->sendActivationMail($user);
            auth()->logout();
            return back()->with('warning', 'Please check your account verification email.');
        }

        if ($user && Auth::attempt($credentials, $remember)) {
            return redirect()->route('my-profile');
        } else if (!$user) {
            return redirect()->back()->with('error', "This email address does not exist!");
        } else {
            return redirect()->back()->with(['error' => 'Incorrect email or password!']);
        }
    }

    // Signup
    public function view_signup()
    {
        return view('login.signup');
    }

    public function post_signup(Request $req)
    {
        $req->flash();
        $req->validate([
            'email' => 'unique:users,email|regex:/(.+)@(.+)\.(.+)/i',
            'password' => 'min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/'
        ],[
            'email.regex' => 'Please enter the correct email format',
            'email.unique' => 'Email already exists',
            'password.min' => 'Please enter a password of more than 8 characters',
            'password.regex' => 'Password must contain uppercase and lowercase characters'
        ]);

        $newUser = User::create([
            'email' => $req->email,
            'name' => $req->name,
            'password' => bcrypt($req->password)
        ]);
        event(new Registered($newUser));

        $this->activationService->sendActivationMail($newUser);
        return redirect('/signup')->with('message', 'Please check your email and verify according to the instructions.');
    }

    public function activate_user($token)
    {
        if ($user = $this->activationService->activateUser($token)) {
            Auth::login($user, $remember = true);
            return redirect('/my-profile');
        } else {
            return redirect()->back()->with('error', 'Tài khoản email của bạn không chính xác');
        }
        abort(404);
    }

    // Login Google
    public function login_google()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback_google()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
            $finduser = User::where('google_id', $user->id)->first();
            $finduseremail = User::where('email', $user->email)->first();

            if ($finduser) {
                Auth::login($finduser);
                return redirect('/my-profile');
            } else if (!$finduseremail) {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'google_id' => $user->id,
                    'active' => true
                ]);
                Auth::login($newUser, true);

                return redirect('/my-profile');
            } else {
                return redirect()->route('login')->with(['warning' => 'Do you already have an account. Please login with your registered email and password.']);
            }
        } catch (Exception $e) {
            dd($e);
            return redirect('/login');
        }
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect('/');
    }
}
