<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\ResetPasswordRequest;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /**
     * Create token password reset.
     *
     * @param  ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function forgot_password()
    {
        return view('login.forgotpassword');
    }

    public function sendMail(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $passwordReset = PasswordReset::updateOrCreate([
                'email' => $user->email,
            ], [
                'token' => Str::random(60),
            ]);
            if ($passwordReset) {
                $user->notify(new ResetPasswordRequest($passwordReset->token, $request->email));
            }

            return redirect()->back()->with([
                'message' => 'Please check your email to change your password.'
            ]);
        }
        return redirect()->back()->with([
            'error' => 'Email address is not registered.'
        ]);
    }

    public function reset_password()
    {
        $email = $_GET['email'];
        $token = $_GET['token'];
        return view('login.newpassword', ['email' => $email, 'token' => $token]);
    }

    public function update_password(Request $request)
    {
        $request->flash();
        $request->validate(
            [
                'password' => 'min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
                'password_confirmation' => 'required|min:8'
            ],
            [
                'password.min' => 'Hãy nhập mật khẩu trên 8 ký tự.',
                'password.confirmed' => 'Xác thực mật khẩu không trùng nhau.',
                'password.regex' => 'Mật khẩu phải chứa ít nhật 1 ký tự hoa, thường, ký tự đặc biệt và số.',
                'password_confirmation.min' => 'Xác thực mật khẩu phải trên 8 ký tự.'
            ]
        );
        $passwordReset = PasswordReset::where('token', $request->token)->first();
        if ($passwordReset) {
            if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
                $passwordReset->delete();

                return redirect()->back(422)->with([
                    'message' => 'Bạn đã hết lượt đổi mật khẩu. Làm ơn hãy yêu cầu đổi lại mật khẩu.',
                ]);
            }
            $user = User::where('email', $passwordReset->email)->firstOrFail();
            $user->update([
                'password' => bcrypt($request->password),
                'active' => true
            ]);
            $passwordReset->delete();

            return redirect()->route('login')->with([
                'success' => 'Thành công',
            ]);
        }
        return redirect()->back(404)->with([
            'error' => 'Tài khoản của bạn không yêu cầu lấy lại mật khẩu.',
        ]);
    }
}
