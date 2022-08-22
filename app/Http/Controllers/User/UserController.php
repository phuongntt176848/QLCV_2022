<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\InviteEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function view_profile()
    {
        $pattern = '/(http)(.+)/';
        $user = User::find(auth()->user()->id);
        $team_me_joined = $user->team()->get();

        return view('user.userprofile', [
            'user' => $user->profile(),
            'isStorage' => preg_match($pattern, $user['avatar']),
            'teams' => $team_me_joined
        ]);
    }

    public function other_profile($id)
    {
        $pattern = '/(http)(.+)/';
        $user = User::find($id);
        $team_me_joined = $user->team()->get();

        return view('user.userprofile', [
            'user' => $user->profile(),
            'isStorage' => preg_match($pattern, $user['avatar']),
            'other' => true,
            'teams' => $team_me_joined
        ]);
    }

    public function edit_name($id, Request $request)
    {
        $user = User::find($id);
        $user->name = $request->user_name;
        $user->save();

        return redirect()->back()->with(['success' => 'Doi ten thanh cong']);
    }

    public function edit_avatar($id, Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:4096'
        ],[
            'avatar.image' => 'Phải có dạng là ảnh',
            'avatar.mimes' => 'Phai co duoi la jpg',
            'avatar.max' => 'Anh co do lon la 4096 kb'
        ]);
        if ($request->hasFile('avatar')) {
            $path = Storage::putFile('teams', $request->file('avatar'));
            $user = User::find($id);
            $user->avatar = $path;
            $user->save();
            return redirect()->back()->with(['success' => 'Upload photo successfully!']);
        }
    }

    public function update_profile(Request $request)
    {
        $request->flash();
        $request->validate([
            'user_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'user_brithday' => 'required|date|before:tomorrow'
        ],[
            'user_phone.min' => '',
            'user_phone.regex' => '',
            'user_brithday.date' => '',
            'user_brithday.before' => ''
        ]);

        User::updateOrCreate(
            ['id' => auth()->user()->id ? auth()->user()->id : $request->id],
            [
                'title' => $request->user_title,
                'phone' => $request->user_phone,
                'location' => $request->user_location,
                'brithday' => $request->user_brithday
            ]
        );
        return redirect()->back()->with(['success' => 'Profile change successfully!']);
    }

    public function invite(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', '=', $email)->first();
        if (!$user) {
            // Invite
            $mailable = new InviteEmail(route('home'), auth()->user()->email);
            Mail::to($email)->send($mailable);
            return back()->with(['success'=>'Email sent successfully!']);
        }

        return back()->with(['error' => 'Email is exited']);
    }
}
