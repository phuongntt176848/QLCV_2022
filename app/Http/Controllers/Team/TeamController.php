<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $team_of_me = [];
        $team_of_other = [];
        $idUser = auth()->user()->id;
        $users = User::select('id', 'title', 'avatar', 'email', 'name')
            ->get();
        $me = User::where('id', '=', $idUser)->first();
        $team_me_joined = $me->team()->get();

        foreach ($team_me_joined as $team) {
            $members = TeamUser::where('idTeam', '=', $team->id)->get();
            $count_user_of_team = count($members);
            array_push($team_of_me, [
                'id' => $team->id,
                'team_name' => $team->name,
                'count_member' => $count_user_of_team,
                'img' => $team->img
            ]);
        }

        foreach ($users as $user) {
            $teams = $user->team()->get();
            if (empty($teams[0])) {
                $team_of_other[$user->id] = [];
            } else {
                $team_of_user = [];
                foreach ($teams as $team) {
                    array_push($team_of_user, [
                        'team_name' => $team->name,
                        'img' => $team->img
                    ]);
                }
                $team_of_other[$user->id] = $team_of_user;
            }
        }

        return view('team.listteam', [
            'users' => $users,
            'count_user' => $users->count(),
            'teamOfMes' => $team_of_me,
            'teamOfOthers' => $team_of_other,
            'idTeam' => 0
        ]);
    }

    public function search_team()
    {
        $team = isset($_GET['name']) ? $_GET['name'] : null;
        $team_of_me = [];
        $team_of_other = [];
        $current_team = [];
        $member_of_teams = [];
        $memberIds = [];
        $idUser = auth()->user()->id;
        $me = User::where('id', '=', $idUser)->first();
        $users = User::select('id', 'title', 'avatar', 'email', 'name')->get();

        $team_me_joined = $me->team()->where('name', 'like', $team . '%')->get();

        if (!empty($team_me_joined[0])){
            $id = $team_me_joined[0]->id;
            $memberTeams = Team::findOrFail($id)->user()->paginate(15);
            $memberTeamsAll = Team::findOrFail($id)->user()->get();

            foreach ($team_me_joined as $team) {
                $members = TeamUser::where('idTeam', '=', $team->id)->get();
                $count_user_of_team = count($members);
                array_push($team_of_me, [
                    'id' => $team->id,
                    'team_name' => $team->name,
                    'count_member' => $count_user_of_team,
                    'img' => $team->img
                ]);
                if ($id == $team->id) {
                    $current_team['id'] = $id;
                    $current_team['team_name'] = $team->name;
                    $current_team['count_member'] = $count_user_of_team;
                    $current_team['img'] = $team->img;
                    $current_team['bg_img'] = $team->bg_img;
                }
            }

            foreach ($memberTeamsAll as $member) {
                $profile = $member->profile();
                array_push($memberIds, $profile['id']);
            }

            foreach ($memberTeams as $member) {
                $profile = $member->profile();
                array_push($member_of_teams, $profile);
            }

            $current_team['members'] = $member_of_teams;
            $current_team['memberIds'] = $memberIds;

            return view('team.detailteam', [
                'users' => $users,
                'count_user' => $users->count(),
                'teamOfMes' => $team_of_me,
                'currentTeam' => $current_team
            ]);
        } else {
            foreach ($users as $user) {
                $teams = $user->team()->get();
                if (empty($teams[0])) {
                    $team_of_other[$user->id] = [];
                } else {
                    $team_of_user = [];
                    foreach ($teams as $team) {
                        array_push($team_of_user, [
                            'team_name' => $team->name,
                            'img' => $team->img
                        ]);
                    }
                    $team_of_other[$user->id] = $team_of_user;
                }
            }
            return view('team.listteam', [
                'users' => $users,
                'count_user' => $users->count(),
                'teamOfMes' => $team_of_me,
                'teamOfOthers' => $team_of_other,
                'idTeam' => 0
            ]);
        }
    }

    public function search_all()
    {
        $email = $_GET['email'];
        $team_of_me = [];
        $team_of_other = [];
        $idUser = auth()->user()->id;
        $users = User::select('id', 'title', 'avatar', 'email', 'name')->get();
        $userSearchs = User::select('id', 'title', 'avatar', 'email', 'name')
            ->where('email', 'like', $email . '%')
            ->get();
        $me = User::where('id', '=', $idUser)->first();
        $team_me_joined = $me->team()->get();

        foreach ($team_me_joined as $team) {
            $members = TeamUser::where('idTeam', '=', $team->id)->get();
            $count_user_of_team = count($members);
            array_push($team_of_me, [
                'id' => $team->id,
                'team_name' => $team->name,
                'count_member' => $count_user_of_team,
                'img' => $team->img
            ]);
        }

        foreach ($userSearchs as $user) {
            $teams = $user->team()->get();
            if (empty($teams[0])) {
                $team_of_other[$user->id] = [];
            } else {
                $team_of_user = [];
                foreach ($teams as $team) {
                    array_push($team_of_user, [
                        'team_name' => $team->name,
                        'img' => $team->img
                    ]);
                }
                $team_of_other[$user->id] = $team_of_user;
            }
        }

        return view('team.listteam', [
            'users' => $userSearchs,
            'count_user' => $users->count(),
            'teamOfMes' => $team_of_me,
            'teamOfOthers' => $team_of_other,
            'idTeam' => 0
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->flash();
        $request->validate([
            'team_name' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:4096'
        ], [
            'team_name.required' => 'Phải nhập tên',
            'image.image' => 'Phải có dạng là ảnh',
            'image.mimes' => 'Phai co duoi la jpg',
            'image.max' => 'Anh co do lon la 4096 kb'
        ]);

        if ($request->hasFile('image')) {
            $path = Storage::putFile('teams', $request->file('image'));
        }

        $team = Team::create([
            'name' => $request->team_name,
            'img' => $path,
            'bg_img' => null
        ]);

        TeamUser::create([
            'idUser' => auth()->user()->id,
            'idTeam' => $team->id,
            'idRole' => 1
        ]);

        if ($request->member) {
            TeamUser::create([
                'idUser' => $request->member,
                'idTeam' => $team->id,
                'idRole' => 2
            ]);
        }

        return redirect()->back()->with(['success' => 'Tao thanh cong']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $team_of_me = [];
        $current_team = [];
        $member_of_teams = [];
        $memberIds = [];
        $idUser = auth()->user()->id;

        $me = User::where('id', '=', $idUser)->first();
        $team_me_joined = $me->team()->get();

        $memberTeams = Team::findOrFail($id)->user()->paginate(15);
        $memberTeamsAll = Team::findOrFail($id)->user()->get();

        foreach ($team_me_joined as $team) {
            $members = TeamUser::where('idTeam', '=', $team->id)->get();
            $count_user_of_team = count($members);
            array_push($team_of_me, [
                'id' => $team->id,
                'team_name' => $team->name,
                'count_member' => $count_user_of_team,
                'img' => $team->img
            ]);
            if ($id == $team->id) {
                $current_team['id'] = $id;
                $current_team['team_name'] = $team->name;
                $current_team['count_member'] = $count_user_of_team;
                $current_team['img'] = $team->img;
                $current_team['bg_img'] = $team->bg_img;
            }
        }

        foreach ($memberTeamsAll as $member) {
            $profile = $member->profile();
            array_push($memberIds, $profile['id']);
        }

        foreach ($memberTeams as $member) {
            $profile = $member->profile();
            array_push($member_of_teams, $profile);
        }

        $current_team['members'] = $member_of_teams;
        $current_team['memberIds'] = $memberIds;
        $users = User::select('id', 'title', 'avatar', 'email', 'name')
            ->get();
        return view('team.detailteam', [
            'users' => $users,
            'count_user' => $users->count(),
            'teamOfMes' => $team_of_me,
            'currentTeam' => $current_team
        ]);
    }

    public function edit_avatar($id, Request $request)
    {
        $team = Team::find($id);
        if (Storage::exists($team->img)) {
            Storage::delete($team->img);
        }
        $path = Storage::putFile('teams/' . $id, $request->file('team_avatar'));
        $team->img = $path;
        $team->save();

        return redirect()->back();
    }

    public function edit_background($id, Request $request)
    {
        $team = Team::find($id);
        if ($team->bg_img && Storage::exists($team->bg_img)) {
            Storage::delete($team->bg_img);
        }
        $path = Storage::putFile('teams/' . $id, $request->file('team_background'));
        $team->bg_img = $path;
        $team->save();

        return redirect()->back();
    }

    public function edit_name($id, Request $request)
    {
        $team = Team::find($id);
        $team->name = $request->team_name;
        $team->save();
        return redirect()->back();
    }

    public function search($id)
    {
        $regex = "/([a-z0-9_]+|[a-z0-9_]+\.[a-z0-9_]+)@(([a-z0-9]|[a-z0-9]+\.[a-z0-9]+)+\.([a-z]{2,4}))/i";
        $search = mb_strtolower($_GET['search'], 'UTF-8');
        $team_of_me = [];
        $current_team = [];
        $memberIds = [];
        $member_of_teams = [];
        $idUser = auth()->user()->id;

        $me = User::where('id', '=', $idUser)->first();
        $team_me_joined = $me->team()->get();

        if (preg_match($regex, $search)) {
            $memberTeams = Team::findOrFail($id)->user()->where('email', 'like', $search . '%')->paginate(15);
        } else {
            $memberTeams = Team::findOrFail($id)->user()->where('name', 'like', $search . '%')->paginate(15);
        }
        $memberTeamsAll = Team::findOrFail($id)->user()->get();

        foreach ($team_me_joined as $team) {
            $members = TeamUser::where('idTeam', '=', $team->id)->get();
            $count_user_of_team = count($members);
            array_push($team_of_me, [
                'id' => $team->id,
                'team_name' => $team->name,
                'count_member' => $count_user_of_team,
                'img' => $team->img
            ]);
            if ($id == $team->id) {
                $current_team['id'] = $id;
                $current_team['team_name'] = $team->name;
                $current_team['count_member'] = $count_user_of_team;
                $current_team['img'] = $team->img;
                $current_team['bg_img'] = $team->bg_img;
            }
        }

        foreach ($memberTeamsAll as $member) {
            $profile = $member->profile();
            array_push($memberIds, $profile['id']);
        }

        foreach ($memberTeams as $member) {
            $profile = $member->profile();
            array_push($member_of_teams, $profile);
        }

        $current_team['members'] = $member_of_teams;
        $current_team['memberIds'] = $memberIds;
        $users = User::select('id', 'title', 'avatar', 'email', 'name')
            ->get();
        return view('team.detailteam', [
            'users' => $users,
            'count_user' => $users->count(),
            'teamOfMes' => $team_of_me,
            'currentTeam' => $current_team
        ]);
    }

    public function add_member($id, Request $request)
    {
        Team::findOrFail($id)->user()->findOrFail(auth()->user()->id);
        if ($request->member) {
            TeamUser::create([
                'idUser' => $request->member,
                'idTeam' => $id,
                'idRole' => 2
            ]);
        }

        return redirect()->back()->with(['success' => 'Tao thanh cong']);
    }

    public function delete_member($id, Request $request)
    {
        Team::findOrFail($id)->user()->findOrFail(auth()->user()->id);
        if ($request->member) {
            TeamUser::where([
                'idUser' => $request->member,
                'idTeam' => $id
            ])->delete();
        }

        return redirect()->back()->with(['success' => 'Tao thanh cong']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_team($id)
    {
        Team::destroy($id);
        return redirect()->route('my-team');
    }
}
